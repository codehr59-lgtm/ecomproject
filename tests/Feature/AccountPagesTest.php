<?php

namespace Tests\Feature;

use Tests\TestCase;

class AccountPagesTest extends TestCase
{
    public function test_pages_render(): void
    {
        $this->get('/login')->assertOk()->assertSee('Welcome back');
        $this->get('/register')->assertOk()->assertSee('Create your account');
        $this->get('/account')->assertOk()->assertSee('My Account');
        $this->get('/wishlist')->assertOk()->assertSee('My Wishlist');
        $this->get('/track')->assertOk()->assertSee('Track Your Order');
    }
}
