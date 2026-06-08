<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use DatabaseTransactions;

    public function test_guest_cannot_access_admin(): void
    {
        $this->get('/admin')->assertRedirect();
    }

    public function test_non_admin_cannot_access_admin(): void
    {
        $u = User::factory()->create(['is_admin' => false]);
        $this->actingAs($u)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_access(): void
    {
        $u = User::factory()->create(['is_admin' => true]);
        $this->actingAs($u)->get('/admin')->assertOk();
    }
}
