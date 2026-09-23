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
            Stat::make('পণ্য', $totalProducts)
                ->description('Active ' . $activeProducts . ' · Low stock ' . $lowStock)
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->icon('heroicon-o-shopping-bag')
                ->color('primary'),

            Stat::make('অর্ডার', $totalOrders)
                ->description('Pending ' . $pendingOrders)
                ->descriptionIcon('heroicon-m-clock')
                ->icon('heroicon-o-shopping-cart')
                ->color('warning'),

            Stat::make('Revenue', '৳ ' . number_format($revenue))
                ->description('Cancelled বাদে সব অর্ডার')
                ->descriptionIcon('heroicon-m-banknotes')
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('কাস্টমার', $totalCustomers)
                ->description('নিবন্ধিত ব্যবহারকারী')
                ->descriptionIcon('heroicon-m-users')
                ->icon('heroicon-o-users')
                ->color('info'),

            Stat::make('ক্যাটাগরি', $totalCategories)
                ->description('সাবক্যাটাগরি ' . $subCategories)
                ->descriptionIcon('heroicon-m-tag')
                ->icon('heroicon-o-tag')
                ->color('success'),
        ];
    }
}
