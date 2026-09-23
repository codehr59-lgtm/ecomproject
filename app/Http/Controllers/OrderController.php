<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Support\PaymentConfig;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * POST /checkout — server-authoritative order placement.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string|max:30',
            'customer_email' => 'nullable|email|max:255',
            'address_line'   => 'required|string|max:255',
            'city'           => 'required|string|max:100',
            'thana'          => 'nullable|string|max:100',
            'notes'          => 'nullable|string|max:1000',
            'payment_method' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $enabled = PaymentConfig::enabledMethods();
                    if (empty($enabled)) {
                        $enabled = ['cod'];
                    }
                    if (! in_array($value, $enabled, true)) {
                        $fail('The selected payment method is not available.');
                    }
                },
            ],
            'coupon_code'    => 'nullable|string|max:50',
            'delivery_zone'  => 'nullable|string|in:inside,outside',
            'items'          => 'required|string',
        ]);

        // Decode cart items from client
        $clientItems = json_decode($validated['items'], true);
        if (! is_array($clientItems) || empty($clientItems)) {
            return back()->withErrors(['items' => 'Cart is empty.']);
        }

        // Build line items using DB prices (never trust client prices)
        $lineItems  = [];
        $subtotal   = 0;

        foreach ($clientItems as $ci) {
            $qty = max(1, (int) ($ci['qty'] ?? 1));

            if (! empty($ci['is_combo'])) {
                $comboId = (int) ($ci['combo_id'] ?? $ci['id'] ?? 0);
                $combo = \App\Models\Combo::with('items.product')->find($comboId);
                if (! $combo) {
                    continue;
                }

                $price = (int) $combo->price;
                $lineTotal = $price * $qty;
                $subtotal += $lineTotal;

                $lineItems[] = [
                    'product_id'    => null,
                    'combo_id'      => $combo->id,
                    'name'          => $combo->name . ' (Combo Pack)',
                    'weight'        => $combo->items_summary ?: 'Combo Package',
                    'price'         => $price,
                    'qty'           => $qty,
                    'line_total'    => $lineTotal,
                    '_is_combo'     => true,
                    '_combo_id'     => $combo->id,
                    '_variation_id' => null,
                ];
                continue;
            }

            $productId = (int) ($ci['id'] ?? 0);

            $product = Product::with('variations')->find($productId);
            if (! $product) {
                continue; // skip unknown products
            }

            $variation = null;
            if ($product->product_type === 'variable' && $product->variations->isNotEmpty()) {
                if (!empty($ci['variation_id'])) {
                    $variation = $product->variations->firstWhere('id', (int) $ci['variation_id']);
                }
                if (!$variation && !empty($ci['weight'])) {
                    $variation = $product->variations->firstWhere('label', $ci['weight']);
                }
                if (!$variation && !empty($ci['price'])) {
                    $variation = $product->variations->firstWhere('price', (int) $ci['price']);
                }
                if (!$variation) {
                    $variation = $product->variations->first();
                }
            }

            if ($variation) {
                $price  = (int) $variation->price;
                $weight = $variation->label;
            } else {
                $price  = (int) ($product->price ?: ($ci['price'] ?? 0));
                $weight = $product->weight ?: ($ci['weight'] ?? null);
            }

            $lineTotal = $price * $qty;
            $subtotal += $lineTotal;

            $lineItems[] = [
                'product_id'    => $product->id,
                'combo_id'      => null,
                'name'          => $product->name,
                'weight'        => $weight,
                'price'         => $price,
                'qty'           => $qty,
                'line_total'    => $lineTotal,
                '_is_combo'     => false,
                '_combo_id'     => null,
                '_variation_id' => $variation ? $variation->id : null,
            ];
        }

        if (empty($lineItems)) {
            return back()->withErrors(['items' => 'No valid items found in cart.']);
        }

        // Delivery fee from settings, based on selected zone
        $freeMin    = (int) \App\Models\Setting::get('free_shipping_min', 1500);
        $insideFee  = (int) \App\Models\Setting::get('delivery_inside_dhaka', 60);
        $outsideFee = (int) \App\Models\Setting::get('delivery_outside_dhaka', 120);

        $zone     = $validated['delivery_zone'] ?? 'inside';
        $zoneFee  = $zone === 'outside' ? $outsideFee : $insideFee;
        $delivery = ($freeMin > 0 && $subtotal >= $freeMin) ? 0 : $zoneFee;

        // Coupon
        $discount    = 0;
        $couponCode  = null;
        $appliedCoupon = null;

        if (! empty($validated['coupon_code'])) {
            $coupon = Coupon::where('code', strtoupper($validated['coupon_code']))
                ->where('is_active', true)
                ->first();

            if ($coupon) {
                $valid = true;

                // Check expiry
                if ($coupon->expires_at && $coupon->expires_at->isPast()) {
                    $valid = false;
                }

                // Check min spend
                if ($valid && $coupon->min_spend && $subtotal < $coupon->min_spend) {
                    $valid = false;
                }

                // Check usage limit
                if ($valid && $coupon->usage_limit && $coupon->used >= $coupon->usage_limit) {
                    $valid = false;
                }

                if ($valid) {
                    $discount = $coupon->type === 'percent'
                        ? (int) round($subtotal * $coupon->value / 100)
                        : (int) $coupon->value;

                    $couponCode    = $coupon->code;
                    $appliedCoupon = $coupon;
                }
            }
        }

        // Total (minimum 0)
        $total = max(0, $subtotal + $delivery - $discount);

        // Resolve or create a customer User for this order
        $phone = $validated['customer_phone'];
        $email = $validated['customer_email'] ?? null;

        $customer = User::where('phone', $phone)->where('is_admin', false)->first();
        if (! $customer && $email) {
            $customer = User::where('email', $email)->where('is_admin', false)->first();
        }
        if (! $customer) {
            $customer = User::create([
                'name'     => $validated['customer_name'],
                'email'    => $email ?? $phone . '@guest.local',
                'phone'    => $phone,
                'password' => bcrypt(Str::random(16)),
                'is_admin' => false,
            ]);
        }
        $userId = $customer->id;

        // Create order + items in a transaction
        $order = DB::transaction(function () use (
            $validated, $lineItems, $subtotal, $delivery, $discount,
            $total, $couponCode, $appliedCoupon, $userId
        ) {
            $order = Order::create([
                'number'         => Order::generateNumber(),
                'user_id'        => $userId,
                'status'         => 'pending',
                'customer_name'  => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'] ?? null,
                'address_line'   => $validated['address_line'],
                'city'           => $validated['city'],
                'thana'          => $validated['thana'] ?? null,
                'notes'          => $validated['notes'] ?? null,
                'subtotal'       => $subtotal,
                'delivery'       => $delivery,
                'discount'       => $discount,
                'total'          => $total,
                'coupon_code'    => $couponCode,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'unpaid',
                'placed_at'      => now(),
            ]);

            foreach ($lineItems as $item) {
                $varId   = $item['_variation_id'] ?? null;
                $isCombo = $item['_is_combo'] ?? false;
                $comboId = $item['_combo_id'] ?? null;
                unset($item['_variation_id'], $item['_is_combo'], $item['_combo_id']);

                $order->items()->create($item);

                if ($isCombo && $comboId) {
                    \App\Models\Combo::where('id', $comboId)
                        ->where('stock', '>', 0)
                        ->decrement('stock', $item['qty']);
                } elseif ($varId) {
                    \App\Models\ProductVariation::where('id', $varId)
                        ->where('stock', '>', 0)
                        ->decrement('stock', $item['qty']);
                    $p = Product::find($item['product_id']);
                    $p?->syncStock();
                } elseif (! empty($item['product_id'])) {
                    Product::where('id', $item['product_id'])
                        ->where('stock', '>', 0)
                        ->decrement('stock', $item['qty']);
                }
            }

            // Increment coupon usage
            if ($appliedCoupon) {
                $appliedCoupon->increment('used');
            }

            return $order;
        });

        // COD: go straight to confirmation; online methods: go to payment gateway
        if ($order->payment_method === 'cod') {
            return redirect()->route('order.confirmation', $order->number);
        }

        return redirect()->route('payment.start', $order->number);
    }

    /**
     * GET /order/{number}/confirmation
     */
    public function confirmation(string $number): \Illuminate\View\View
    {
        $order = Order::with('items')->where('number', $number)->firstOrFail();

        // Authorization:
        // - Guest orders (user_id null): accessible by number (capability URL for COD guests).
        // - Authenticated user viewing an owned order: allowed.
        // - Authenticated admin viewing any order: allowed (via OrderPolicy).
        // - Authenticated non-owner viewing another user's order: 403.
        if (auth()->check() && $order->user_id !== null) {
            $user = auth()->user();
            if (! $user->is_admin && $user->id !== $order->user_id) {
                abort(403);
            }
        }

        return view('pages.order-confirmation', compact('order'));
    }

    /**
     * GET /order/{number}/invoice — download PDF
     */
    public function invoice(string $number): \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
    {
        $order = Order::with('items')->where('number', $number)->firstOrFail();

        // Authorization: same rules as confirmation.
        if (auth()->check() && $order->user_id !== null) {
            $user = auth()->user();
            if (! $user->is_admin && $user->id !== $order->user_id) {
                abort(403);
            }
        }

        $pdf = Pdf::loadView('invoices.order', compact('order'));

        return $pdf->download('invoice-' . $order->number . '.pdf');
    }

    public function packingSlip(string $number): \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
    {
        $order = Order::with('items')->where('number', $number)->firstOrFail();

        if (auth()->check() && $order->user_id !== null) {
            $user = auth()->user();
            if (! $user->is_admin && $user->id !== $order->user_id) {
                abort(403);
            }
        }

        $pdf = Pdf::loadView('invoices.packing-slip', compact('order'));

        return $pdf->download('packing-slip-' . $order->number . '.pdf');
    }

    public function shippingLabel(string $number): \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
    {
        $order = Order::with('items')->where('number', $number)->firstOrFail();

        if (auth()->check() && $order->user_id !== null) {
            $user = auth()->user();
            if (! $user->is_admin && $user->id !== $order->user_id) {
                abort(403);
            }
        }

        $pdf = Pdf::loadView('invoices.shipping-label', compact('order'))
            ->setPaper([0, 0, 288, 432]); // 4x6 inches

        return $pdf->download('label-' . $order->number . '.pdf');
    }
}
