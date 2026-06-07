<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProductPageTest extends TestCase
{
    public function test_product_page_renders_signature_buttons(): void
    {
        $this->get('/product/mustard-oil-1l')
            ->assertOk()
            ->assertSee('Pure Mustard Oil 1L')
            ->assertSee('Add To Cart')
            ->assertSee('Buy Now')
            ->assertSee('Order On WhatsApp')
            ->assertSee('Call For Order')
            ->assertSee('Related products');
    }

    public function test_unknown_product_is_404(): void
    {
        $this->get('/product/nope')->assertNotFound();
    }
}
