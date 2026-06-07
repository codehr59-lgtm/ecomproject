<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_home_renders_with_signature_sections(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Top Selling')
            ->assertSee('Featured Categories')
            ->assertSee('SHOPPING CART'); // cart drawer present via layout
    }
}
