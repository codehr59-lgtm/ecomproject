<?php

namespace Tests\Feature;

use Tests\TestCase;

class CheckoutPageTest extends TestCase
{
    public function test_checkout_renders(): void
    {
        $this->get('/checkout')->assertOk()
            ->assertSee('Delivery Details')->assertSee('Payment Method')
            ->assertSee('Place Order')->assertSee('Order Summary');
    }
}
