<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_cannot_view_another_users_order_invoice(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $order = Order::create([
            'number'          => 'SHV-TEST01',
            'user_id'         => $owner->id,
            'status'          => 'pending',
            'customer_name'   => 'Owner',
            'customer_phone'  => '01700000000',
            'address_line'    => '1 Road',
            'city'            => 'Dhaka',
            'subtotal'        => 100,
            'delivery'        => 60,
            'discount'        => 0,
            'total'           => 160,
            'payment_method'  => 'cod',
            'payment_status'  => 'unpaid',
            'placed_at'       => now(),
        ]);

        $this->actingAs($other)
            ->get('/order/SHV-TEST01/invoice')
            ->assertForbidden();

        $this->actingAs($owner)
            ->get('/order/SHV-TEST01/invoice')
            ->assertOk();
    }

    public function test_user_cannot_view_another_users_order_confirmation(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $order = Order::create([
            'number'          => 'SHV-TEST02',
            'user_id'         => $owner->id,
            'status'          => 'pending',
            'customer_name'   => 'Owner2',
            'customer_phone'  => '01700000000',
            'address_line'    => '1 Road',
            'city'            => 'Dhaka',
            'subtotal'        => 100,
            'delivery'        => 60,
            'discount'        => 0,
            'total'           => 160,
            'payment_method'  => 'cod',
            'payment_status'  => 'unpaid',
            'placed_at'       => now(),
        ]);

        $this->actingAs($other)
            ->get('/order/SHV-TEST02/confirmation')
            ->assertForbidden();

        $this->actingAs($owner)
            ->get('/order/SHV-TEST02/confirmation')
            ->assertOk();
    }

    public function test_guest_order_confirmation_accessible_by_number(): void
    {
        // Guest orders (user_id = null) are accessible by order number — this is the
        // "capability URL" pattern for COD guest orders.
        $order = Order::create([
            'number'          => 'SHV-TEST03',
            'user_id'         => null,
            'status'          => 'pending',
            'customer_name'   => 'Guest',
            'customer_phone'  => '01700000000',
            'address_line'    => '1 Road',
            'city'            => 'Dhaka',
            'subtotal'        => 200,
            'delivery'        => 60,
            'discount'        => 0,
            'total'           => 260,
            'payment_method'  => 'cod',
            'payment_status'  => 'unpaid',
            'placed_at'       => now(),
        ]);

        // Un-authenticated access to a guest order should be allowed
        $this->get('/order/SHV-TEST03/confirmation')
            ->assertOk();
    }

    public function test_security_headers_present(): void
    {
        $this->get('/')->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->get('/')->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $this->get('/')->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_admin_can_view_any_order(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $owner = User::factory()->create();

        $order = Order::create([
            'number'          => 'SHV-TEST04',
            'user_id'         => $owner->id,
            'status'          => 'pending',
            'customer_name'   => 'SomeUser',
            'customer_phone'  => '01700000000',
            'address_line'    => '1 Road',
            'city'            => 'Dhaka',
            'subtotal'        => 100,
            'delivery'        => 60,
            'discount'        => 0,
            'total'           => 160,
            'payment_method'  => 'cod',
            'payment_status'  => 'unpaid',
            'placed_at'       => now(),
        ]);

        // Admin viewing someone else's order invoice should be permitted
        $this->actingAs($admin)
            ->get('/order/SHV-TEST04/invoice')
            ->assertOk();
    }
}
