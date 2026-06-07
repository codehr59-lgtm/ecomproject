<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AccountPagesTest extends TestCase
{
    use DatabaseTransactions;

    public function test_public_pages_render(): void
    {
        $this->get('/login')->assertOk()->assertSee('Welcome back');
        $this->get('/register')->assertOk()->assertSee('Create your account');
        $this->get('/track')->assertOk()->assertSee('Track Your Order');
    }

    public function test_account_requires_auth(): void
    {
        $this->get('/account')->assertRedirect('/login');
    }

    public function test_wishlist_requires_auth(): void
    {
        $this->get('/wishlist')->assertRedirect('/login');
    }

    public function test_auth_user_sees_account(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/account')->assertOk()->assertSee('My Account');
    }

    public function test_auth_user_sees_wishlist(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/wishlist')->assertOk()->assertSee('My Wishlist');
    }
}
