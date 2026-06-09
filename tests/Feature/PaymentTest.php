<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // Enable all payment methods for payment flow tests
        Setting::set('cod_enabled',        true);
        Setting::set('bkash_enabled',      true);
        Setting::set('sslcommerz_enabled', true);
        Cache::forget('app.settings');
    }

    private function place(string $method): \Illuminate\Testing\TestResponse
    {
        $p     = Product::first();
        $items = json_encode([['id' => $p->id, 'name' => $p->name, 'weight' => $p->weight, 'price' => $p->price, 'cat' => 'x', 'qty' => 1]]);

        return $this->post('/checkout', [
            'customer_name'  => 'Pay ' . $method,
            'customer_phone' => '01700000000',
            'customer_email' => 'p@ex.com',
            'address_line'   => 'x',
            'city'           => 'Dhaka',
            'payment_method' => $method,
            'items'          => $items,
        ]);
    }

    public function test_cod_goes_straight_to_confirmation(): void
    {
        $r = $this->place('cod');
        $r->assertRedirect();

        $o = Order::where('customer_name', 'Pay cod')->latest()->first();
        $this->assertNotNull($o);
        $this->assertEquals('cod', $o->payment_method);
        $r->assertRedirect(route('order.confirmation', $o->number));
    }

    public function test_bkash_routes_to_payment_start(): void
    {
        $r = $this->place('bkash');
        $o = Order::where('customer_name', 'Pay bkash')->latest()->first();
        $this->assertNotNull($o);
        $r->assertRedirect(route('payment.start', $o->number));
    }

    public function test_sslcommerz_routes_to_payment_start(): void
    {
        $r = $this->place('sslcommerz');
        $o = Order::where('customer_name', 'Pay sslcommerz')->latest()->first();
        $this->assertNotNull($o);
        $r->assertRedirect(route('payment.start', $o->number));
    }

    public function test_payment_start_unconfigured_falls_back_to_confirmation(): void
    {
        // With no creds in test env, payment.start should not crash;
        // it redirects to confirmation (pending payment).
        $this->place('bkash');
        $o = Order::where('customer_name', 'Pay bkash')->latest()->first();
        $this->assertNotNull($o);

        $this->get(route('payment.start', $o->number))
            ->assertRedirect(route('order.confirmation', $o->number));
    }

    public function test_payment_start_sslcommerz_unconfigured_falls_back(): void
    {
        $this->place('sslcommerz');
        $o = Order::where('customer_name', 'Pay sslcommerz')->latest()->first();
        $this->assertNotNull($o);

        $this->get(route('payment.start', $o->number))
            ->assertRedirect(route('order.confirmation', $o->number));
    }

    public function test_already_paid_order_skips_gateway(): void
    {
        $this->place('bkash');
        $o = Order::where('customer_name', 'Pay bkash')->latest()->first();
        $this->assertNotNull($o);

        // Mark as paid
        $o->update(['payment_status' => 'paid']);

        // Visiting payment.start on a paid order should just redirect to confirmation
        $this->get(route('payment.start', $o->number))
            ->assertRedirect(route('order.confirmation', $o->number));
    }

    public function test_confirmation_page_shows_complete_payment_button_for_unpaid_online_order(): void
    {
        $this->place('bkash');
        $o = Order::where('customer_name', 'Pay bkash')->latest()->first();
        $this->assertNotNull($o);

        $this->get(route('order.confirmation', $o->number))
            ->assertOk()
            ->assertSee('Complete Payment');
    }

    public function test_confirmation_page_does_not_show_complete_payment_for_cod(): void
    {
        $this->place('cod');
        $o = Order::where('customer_name', 'Pay cod')->latest()->first();
        $this->assertNotNull($o);

        $this->get(route('order.confirmation', $o->number))
            ->assertOk()
            ->assertDontSee('Complete Payment');
    }

    public function test_sslcommerz_fail_marks_order_failed(): void
    {
        $this->place('sslcommerz');
        $o = Order::where('customer_name', 'Pay sslcommerz')->latest()->first();
        $this->assertNotNull($o);

        // Simulate SSLCommerz fail callback (no CSRF needed — excluded)
        $this->post(route('payment.sslcommerz.fail'), [
            'tran_id' => $o->number,
            'status'  => 'FAILED',
        ])->assertRedirect();

        $o->refresh();
        $this->assertEquals('failed', $o->payment_status);
    }

    public function test_sslcommerz_cancel_does_not_change_payment_status(): void
    {
        $this->place('sslcommerz');
        $o = Order::where('customer_name', 'Pay sslcommerz')->latest()->first();
        $this->assertNotNull($o);

        $this->post(route('payment.sslcommerz.cancel'), [
            'tran_id' => $o->number,
        ])->assertRedirect();

        $o->refresh();
        // Status should remain unpaid (not changed on cancel)
        $this->assertEquals('unpaid', $o->payment_status);
    }
}
