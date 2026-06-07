<?php

namespace Tests\Feature;

use Tests\TestCase;

class ShopPageTest extends TestCase
{
    public function test_shop_renders(): void
    {
        $this->get('/shop')->assertOk()->assertSee('Categories')->assertSee('Sundarban Wild Honey');
    }

    public function test_category_filter_renders(): void
    {
        $this->get('/shop?cat=honey')->assertOk()->assertSee('Honey');
    }

    public function test_category_alias_renders(): void
    {
        $this->get('/category/dates')->assertOk()->assertSee('Dates');
    }

    public function test_unknown_category_alias_404(): void
    {
        $this->get('/category/nope')->assertNotFound();
    }
}
