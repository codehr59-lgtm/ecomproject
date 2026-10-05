<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class HeaderMenuSeeder extends Seeder
{
    public function run(): void
    {
        $menu = Menu::firstOrCreate(
            ['location' => 'header'],
            ['name' => 'Main Header Navigation']
        );

        if ($menu->items()->count() === 0) {
            $defaultItems = [
                ['label' => 'Home', 'url' => '/', 'type' => 'custom', 'sort' => 1],
                ['label' => 'All Products', 'url' => '/shop', 'type' => 'custom', 'sort' => 2],
                ['label' => 'Combo Offers', 'url' => '/combos', 'type' => 'custom', 'sort' => 3],
                ['label' => 'Track Order', 'url' => '/track', 'type' => 'custom', 'sort' => 4],
                ['label' => 'About Us', 'url' => '/about', 'type' => 'custom', 'sort' => 5],
                ['label' => 'Contact Us', 'url' => '/contact', 'type' => 'custom', 'sort' => 6],
            ];

            foreach ($defaultItems as $item) {
                $menu->items()->create(array_merge($item, [
                    'is_active' => true,
                    'target'    => '_self',
                ]));
            }
        }

        Cache::forget('menu.location.header');
    }
}
