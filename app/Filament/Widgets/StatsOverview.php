<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Products', Product::count())
                ->description('Active: ' . Product::where('is_active', true)->count())
                ->icon('heroicon-o-shopping-bag')
                ->color('primary'),
            Stat::make('Categories', Category::count())
                ->description('Active: ' . Category::where('is_active', true)->count())
                ->icon('heroicon-o-tag')
                ->color('success'),
            Stat::make('Customers', User::where('is_admin', false)->count())
                ->description('Registered users')
                ->icon('heroicon-o-users')
                ->color('info'),
            Stat::make('Orders', 'Coming Soon')
                ->description('Full report in Phase B4')
                ->icon('heroicon-o-shopping-cart')
                ->color('warning'),
        ];
    }
}
