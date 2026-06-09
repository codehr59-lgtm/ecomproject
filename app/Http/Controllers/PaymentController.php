<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Payments\BkashService;
use App\Services\Payments\SslcommerzService;
use Illuminate\Http\Request;
use Throwable;

class PaymentController extends Controller
{
    // ── Entry point for online payment methods ────────────────────────────

    /**
     * GET /payment/{order:number}
     * Routes the customer to the correct payment gateway, or gracefully falls
     * back to the confirmation page if the gateway is not configured.
     */
    public function start(string $number): \Illuminate\Http\RedirectResponse
    {
        $order = Order::where('number', $number)->firstOrFail();

        // Already paid — just go to confirmation
        if ($order->payment_status === 'paid') {
            return redirect()->route('order.confirmation', $order->number);
        }

        $method = $order->payment_method;

        try {
            if ($method === 'bkash') {
                $svc = app(BkashService::class);

                if (! $svc->isConfigured()) {
                    return $this->unconfiguredRedirect($order);
                }

                $result = $svc->createPayment($order);

                // Store paymentID against the order for callback lookup
                $order->update(['payment_ref' => $result['paymentID']]);

                return redirect()->away($result['bkashURL']);
            }

            if ($method === 'sslcommerz') {
                $svc = app(SslcommerzService::class);

                if (! $svc->isConfigured()) {
                    return $this->unconfiguredRedirect($order);
                }

                $url = $svc->initiate($order);

                return redirect()->away($url);
            }
        } catch (Throwable $e) {
            report($e);
            return redirect()->route('order.confirmation', $order->number)
                ->with('error', 'Payment gateway error: ' . $e->getMessage() . '. Your order is placed as pending.');
        }

        // Unknown method — fall through to confirmation
        return redirect()->route('order.confirmation', $order->number);
    }

    // ── SSLCommerz callbacks ──────────────────────────────────────────────

    /**
     * POST /payment/sslcommerz/success
     */
    public function sslSuccess(Request $request): \Illuminate\Http\RedirectResponse
    {
        $order = Order::where('number', $request->input('tran_id'))->first();

        if (! $order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        try {
            $svc = app(SslcommerzService::class);

            if ($svc->validate($request->all(), $order)) {
                $this->markPaid($order, $request->input('val_id'));
                return redirect()->route('order.confirmation', $order->number)
                    ->with('success', 'Payment successful!');
            }
        } catch (Throwable $e) {
            report($e);
        }

        $order->update(['payment_status' => 'failed']);
        return redirect()->route('order.confirmation', $order->number)
            ->with('error', 'Payment validation failed. Please contact support.');
    }

    /**
     * POST /payment/sslcommerz/fail
     */
    public function sslFail(Request $request): \Illuminate\Http\RedirectResponse
    {
        $order = Order::where('number', $request->input('tran_id'))->first();

        if ($order && $order->payment_status === 'unpaid') {
            $order->update(['payment_status' => 'failed']);
        }

        return redirect()->route(
            $order ? 'order.confirmation' : 'checkout',
            $order ? [$order->number] : []
        )->with('error', 'Payment failed. Please try again or choose Cash on Delivery.');
    }

    /**
     * POST /payment/sslcommerz/cancel
     */
    public function sslCancel(Request $request): \Illuminate\Http\RedirectResponse
    {
        $order = Order::where('number', $request->input('tran_id'))->first();

        return redirect()->route(
            $order ? 'order.confirmation' : 'checkout',
            $order ? [$order->number] : []
        )->with('info', 'Payment cancelled. Your order is saved — you can complete payment anytime.');
    }

    /**
     * POST /payment/sslcommerz/ipn  (server-to-server, no session)
     */
    public function sslIpn(Request $request): \Illuminate\Http\Response
    {
        $order = Order::where('number', $request->input('tran_id'))->first();

        if (! $order) {
            return response('Order not found', 404);
        }

        try {
            $svc = app(SslcommerzService::class);

            if ($svc->validate($request->all(), $order)) {
                $this->markPaid($order, $request->input('val_id'));
            }
        } catch (Throwable $e) {
            report($e);
            return response('Error', 500);
        }

        return response('OK', 200);
    }

    // ── bKash callback ────────────────────────────────────────────────────

    /**
     * GET /payment/bkash/callback?paymentID=...&status=success|failure|cancel
     */
    public function bkashCallback(Request $request): \Illuminate\Http\RedirectResponse
    {
        $paymentID = $request->query('paymentID');
        $status    = $request->query('status');

        // Find order by stored payment_ref
        $order = Order::where('payment_ref', $paymentID)->first();

        if (! $order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        if ($status !== 'success') {
            if ($order->payment_status === 'unpaid') {
                $order->update(['payment_status' => $status === 'cancel' ? 'unpaid' : 'failed']);
            }
            $msg = $status === 'cancel'
                ? 'Payment cancelled. Your order is saved — complete payment anytime.'
                : 'bKash payment failed. Please try again.';
            return redirect()->route('order.confirmation', $order->number)->with('error', $msg);
        }

        try {
            $svc    = app(BkashService::class);
            $result = $svc->executePayment($paymentID);

            if (($result['transactionStatus'] ?? '') === 'Completed') {
                $trxID = $result['trxID'] ?? $paymentID;
                $this->markPaid($order, $trxID);
                return redirect()->route('order.confirmation', $order->number)
                    ->with('success', 'bKash payment successful! TrxID: ' . $trxID);
            }
        } catch (Throwable $e) {
            report($e);
        }

        $order->update(['payment_status' => 'failed']);
        return redirect()->route('order.confirmation', $order->number)
            ->with('error', 'bKash payment could not be confirmed. Please contact support.');
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    /**
     * Mark an order as paid (idempotent).
     */
    private function markPaid(Order $order, ?string $ref = null): void
    {
        if ($order->payment_status === 'paid') {
            return; // already processed
        }

        $order->update([
            'payment_status' => 'paid',
            'status'         => $order->status === 'pending' ? 'confirmed' : $order->status,
            'payment_ref'    => $ref ?? $order->payment_ref,
        ]);
    }

    /**
     * Redirect to confirmation with a flash message when gateway is unconfigured.
     */
    private function unconfiguredRedirect(Order $order): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('order.confirmation', $order->number)
            ->with('info', 'Online payment isn\'t configured yet. Your order is placed as pending — pay via the link we send, or choose Cash on Delivery.');
    }
}
