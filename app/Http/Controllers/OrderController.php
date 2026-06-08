<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'payment_method' => 'required|in:cod,sslcommerz,bkash,nagad,rocket',
            'coupon_code'    => 'nullable|string|max:50',
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
            $productId = (int) ($ci['id'] ?? 0);
            $qty       = max(1, (int) ($ci['qty'] ?? 1));

            $product = Product::find($productId);
            if (! $product) {
                continue; // skip unknown products
            }

            $price     = (int) $product->price;
            $lineTotal = $price * $qty;
            $subtotal += $lineTotal;

            $lineItems[] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'weight'     => $product->weight ?? null,
                'price'      => $price,
                'qty'        => $qty,
                'line_total' => $lineTotal,
            ];
        }

        if (empty($lineItems)) {
            return back()->withErrors(['items' => 'No valid items found in cart.']);
        }

        // Delivery fee (free shipping over ৳1,500)
        $delivery = $subtotal >= 1500 ? 0 : 60;

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

        // Create order + items in a transaction
        $order = DB::transaction(function () use (
            $validated, $lineItems, $subtotal, $delivery, $discount,
            $total, $couponCode, $appliedCoupon
        ) {
            $order = Order::create([
                'number'         => Order::generateNumber(),
                'user_id'        => auth()->check() ? auth()->id() : null,
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
                $order->items()->create($item);

                // Decrement stock (guard against negative)
                Product::where('id', $item['product_id'])
                    ->where('stock', '>', 0)
                    ->decrement('stock');
            }

            // Increment coupon usage
            if ($appliedCoupon) {
                $appliedCoupon->increment('used');
            }

            return $order;
        });

        return redirect()->route('order.confirmation', $order->number);
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
}
