<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use DatabaseTransactions;

    public function test_place_order_creates_order_and_items(): void
    {
        $p = Product::first();
        $this->assertNotNull($p, 'Need at least one product in DB');

        $items = json_encode([['id' => $p->id, 'name' => $p->name, 'weight' => $p->weight, 'price' => $p->price, 'cat' => 'x', 'qty' => 2]]);

        $res = $this->post('/checkout', [
            'customer_name'  => 'Buyer',
            'customer_phone' => '01700000000',
            'address_line'   => '123 Rd',
            'city'           => 'Dhaka',
            'payment_method' => 'cod',
            'items'          => $items,
        ]);

        $res->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'customer_name'  => 'Buyer',
            'payment_method' => 'cod',
        ]);

        $order = Order::where('customer_name', 'Buyer')->latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals($p->price * 2, $order->subtotal);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'qty'      => 2,
        ]);
    }

    public function test_confirmation_page_renders(): void
    {
        $p = Product::first();
        $this->assertNotNull($p);

        $items = json_encode([['id' => $p->id, 'name' => $p->name, 'weight' => $p->weight, 'price' => $p->price, 'cat' => 'x', 'qty' => 1]]);

        $this->post('/checkout', [
            'customer_name'  => 'B2',
            'customer_phone' => '01700000000',
            'address_line'   => 'x',
            'city'           => 'Dhaka',
            'payment_method' => 'cod',
            'items'          => $items,
        ]);

        $o = Order::where('customer_name', 'B2')->latest()->first();
        $this->assertNotNull($o);

        $this->get('/order/' . $o->number . '/confirmation')
            ->assertOk()
            ->assertSee($o->number);
    }

    public function test_invoice_downloads(): void
    {
        $p = Product::first();
        $this->assertNotNull($p);

        $items = json_encode([['id' => $p->id, 'name' => $p->name, 'weight' => $p->weight, 'price' => $p->price, 'cat' => 'x', 'qty' => 1]]);

        $this->post('/checkout', [
            'customer_name'  => 'B3',
            'customer_phone' => '01700000000',
            'address_line'   => 'x',
            'city'           => 'Dhaka',
            'payment_method' => 'cod',
            'items'          => $items,
        ]);

        $o = Order::where('customer_name', 'B3')->latest()->first();
        $this->assertNotNull($o);

        $this->get('/order/' . $o->number . '/invoice')
            ->assertOk();
    }

    public function test_server_uses_db_price_not_client_price(): void
    {
        $p = Product::first();
        $this->assertNotNull($p);

        // Try to send a fake price
        $items = json_encode([['id' => $p->id, 'name' => $p->name, 'weight' => $p->weight, 'price' => 1, 'cat' => 'x', 'qty' => 1]]);

        $this->post('/checkout', [
            'customer_name'  => 'PriceChecker',
            'customer_phone' => '01700000000',
            'address_line'   => 'x',
            'city'           => 'Dhaka',
            'payment_method' => 'cod',
            'items'          => $items,
        ]);

        $o = Order::where('customer_name', 'PriceChecker')->latest()->first();
        $this->assertNotNull($o);

        // The order should use real DB price, not the fake price=1
        $this->assertEquals($p->price, $o->subtotal);
    }

    public function test_free_delivery_over_threshold(): void
    {
        // Find or create a product with price >= 1500 (or use 2 items)
        $p = Product::where('price', '>=', 1500)->first()
            ?? Product::first();

        $qty  = $p->price >= 1500 ? 1 : (int) ceil(1500 / max(1, $p->price)) + 1;
        $items = json_encode([['id' => $p->id, 'name' => $p->name, 'weight' => $p->weight, 'price' => $p->price, 'cat' => 'x', 'qty' => $qty]]);

        $this->post('/checkout', [
            'customer_name'  => 'FreeShipTest',
            'customer_phone' => '01700000000',
            'address_line'   => 'x',
            'city'           => 'Dhaka',
            'payment_method' => 'cod',
            'items'          => $items,
        ]);

        $o = Order::where('customer_name', 'FreeShipTest')->latest()->first();
        $this->assertNotNull($o);

        if ($o->subtotal >= 1500) {
            $this->assertEquals(0, $o->delivery);
        } else {
            $this->assertEquals(60, $o->delivery);
        }
    }

    public function test_track_page_shows_order_by_number(): void
    {
        $p = Product::first();
        $this->assertNotNull($p);

        $items = json_encode([['id' => $p->id, 'name' => $p->name, 'weight' => $p->weight, 'price' => $p->price, 'cat' => 'x', 'qty' => 1]]);

        $this->post('/checkout', [
            'customer_name'  => 'TrackTester',
            'customer_phone' => '01700000000',
            'address_line'   => 'x',
            'city'           => 'Dhaka',
            'payment_method' => 'cod',
            'items'          => $items,
        ]);

        $o = Order::where('customer_name', 'TrackTester')->latest()->first();
        $this->assertNotNull($o);

        $this->get('/track?number=' . $o->number)
            ->assertOk()
            ->assertSee($o->number);
    }
}
