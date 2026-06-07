<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProductPageTest extends TestCase
{
    public function test_product_renders(): void
    {
        $this->get('/product/1')->assertOk()
            ->assertSee('Sundarban Wild Honey')
            ->assertSee('Add to Cart')
            ->assertSee('Buy Now')
            ->assertSee('You may also like');
    }

    public function test_unknown_product_404(): void
    {
        $this->get('/product/9999')->assertNotFound();
    }
}
