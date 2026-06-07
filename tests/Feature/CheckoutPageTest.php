<?php

namespace Tests\Feature;

use Tests\TestCase;

class CheckoutPageTest extends TestCase
{
    public function test_checkout_renders_sections(): void
    {
        $this->get('/checkout')
            ->assertOk()
            ->assertSee('Order review')
            ->assertSee('Shipping Address')
            ->assertSee('Payment method')
            ->assertSee('Place Order');
    }
}
