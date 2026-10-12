<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalProducts  = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $lowStock       = Product::where('stock', '<', 10)->where('product_type', 'simple')->count();

        $totalOrders   = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();

        $totalCustomers = User::where('is_admin', false)->count();

        $totalCategories = Category::whereNull('parent_id')->count();
        $subCategories   = Category::whereNotNull('parent_id')->count();

        $revenue = Order::where('status', '!=', 'cancelled')->sum('total');

        return [
            Stat::make('Products', $totalProducts)
                ->description('Active ' . $activeProducts . ' · Low stock ' . $lowStock)
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->icon('heroicon-o-shopping-bag')
                ->color('primary'),

            Stat::make('Orders', $totalOrders)
                ->description('Pending ' . $pendingOrders)
                ->descriptionIcon('heroicon-m-clock')
                ->icon('heroicon-o-shopping-cart')
                ->color('warning'),

            Stat::make('Revenue', '৳ ' . number_format($revenue))
                ->description('All orders excluding cancelled')
                ->descriptionIcon('heroicon-m-banknotes')
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Customers', $totalCustomers)
                ->description('Registered customers')
                ->descriptionIcon('heroicon-m-users')
                ->icon('heroicon-o-users')
                ->color('info'),

            Stat::make('Categories', $totalCategories)
                ->description('Subcategories: ' . $subCategories)
                ->descriptionIcon('heroicon-m-tag')
                ->icon('heroicon-o-tag')
                ->color('success'),
        ];
    }
}
