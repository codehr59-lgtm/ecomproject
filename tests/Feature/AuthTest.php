<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function test_register_creates_user(): void
    {
        $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 't' . uniqid() . '@ex.com',
            'phone'                 => '01700000000',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $this->assertDatabaseHas('users', ['name' => 'Test User']);
    }

    public function test_account_requires_auth(): void
    {
        $this->get('/account')->assertRedirect('/login');
    }

    public function test_auth_user_sees_account(): void
    {
        $u = User::factory()->create();
        $this->actingAs($u)->get('/account')->assertOk();
    }

    public function test_login_screen_renders(): void
    {
        $this->get('/login')->assertOk()->assertSee('Welcome back');
    }
}
