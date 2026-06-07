<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_home_renders(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Top Selling Products')
            ->assertSee('Featured Categories')
            ->assertSee('Exclusive Combo Deals')
            ->assertSee('Your Cart'); // cart drawer present
    }
}
