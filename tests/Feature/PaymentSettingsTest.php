<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PaymentSettingsTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // Clear cache before each test so settings changes are reflected
        Cache::forget('app.settings');
    }

    public function test_settings_page_admin_only(): void
    {
        $this->get('/admin/payment-settings')->assertRedirect(); // guest

        $u = User::factory()->create(['is_admin' => false]);
        $this->actingAs($u)->get('/admin/payment-settings')->assertForbidden();
    }

    public function test_admin_can_open_settings(): void
    {
        $u = User::factory()->create(['is_admin' => true]);
        $this->actingAs($u)->get('/admin/payment-settings')->assertOk();
    }

    public function test_setting_set_get_roundtrip(): void
    {
        Setting::set('bkash_enabled', true);
        $this->assertTrue((bool) Setting::get('bkash_enabled'));

        Setting::set('bkash_app_secret', 'supersecret');
        $this->assertEquals('supersecret', Setting::secret('bkash_app_secret'));

        // Verify it is stored encrypted (not as plain text)
        $raw = \DB::table('settings')->where('key', 'bkash_app_secret')->value('value');
        $this->assertNotEquals('supersecret', $raw);
    }

    public function test_checkout_only_shows_enabled_methods(): void
    {
        Setting::set('cod_enabled', true);
        Setting::set('bkash_enabled', false);
        Setting::set('sslcommerz_enabled', false);

        Cache::forget('app.settings');

        $this->get('/checkout')
            ->assertOk()
            ->assertSee('Cash on Delivery')
            ->assertDontSee('Pay securely with bKash');

        Setting::set('bkash_enabled', true);
        Cache::forget('app.settings');

        $this->get('/checkout')
            ->assertSee('Pay securely with bKash');
    }

    public function test_order_rejects_disabled_method(): void
    {
        Setting::set('bkash_enabled', false);
        Cache::forget('app.settings');

        $p = Product::first();
        $this->assertNotNull($p, 'Need at least one product in DB');

        $items = json_encode([
            [
                'id'     => $p->id,
                'name'   => $p->name,
                'weight' => $p->weight,
                'price'  => $p->price,
                'cat'    => 'x',
                'qty'    => 1,
            ],
        ]);

        $this->post('/checkout', [
            'customer_name'  => 'X',
            'customer_phone' => '01700000000',
            'address_line'   => 'a',
            'city'           => 'Dhaka',
            'payment_method' => 'bkash',
            'items'          => $items,
        ])->assertSessionHasErrors('payment_method');
    }
}
