<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\Product;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SalesReport extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationLabel = 'Sales Report';
    protected static ?string $title           = 'Sales Report';
    protected static string  $view            = 'filament.pages.sales-report';
    protected static ?int    $navigationSort  = 15;

    public string $period = '30';

    public function mount(): void
    {
        $this->period = request('period', '30');
    }

    public function getViewData(): array
    {
        $days = (int) $this->period;
        $from = Carbon::now()->subDays($days)->startOfDay();

        $orders = Order::where('placed_at', '>=', $from)
            ->where('status', '!=', 'cancelled');

        $totalRevenue    = (clone $orders)->sum('total');
        $totalOrders     = (clone $orders)->count();
        $avgOrderValue   = $totalOrders > 0 ? round($totalRevenue / $totalOrders) : 0;
        $paidOrders      = (clone $orders)->where('payment_status', 'paid')->count();
        $unpaidOrders    = (clone $orders)->where('payment_status', 'unpaid')->count();
        $codOrders       = (clone $orders)->where('payment_method', 'cod')->count();
        $onlineOrders    = $totalOrders - $codOrders;

        $dailyRevenue = Order::where('placed_at', '>=', $from)
            ->where('status', '!=', 'cancelled')
            ->select(DB::raw('DATE(placed_at) as date'), DB::raw('SUM(total) as revenue'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $statusBreakdown = Order::where('placed_at', '>=', $from)
            ->select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as revenue'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $paymentMethodBreakdown = Order::where('placed_at', '>=', $from)
            ->where('status', '!=', 'cancelled')
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as revenue'))
            ->groupBy('payment_method')
            ->get();

        $topProducts = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.placed_at', '>=', $from)
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'order_items.name',
                DB::raw('SUM(order_items.qty) as total_qty'),
                DB::raw('SUM(order_items.line_total) as total_revenue')
            )
            ->groupBy('order_items.name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        $topCities = Order::where('placed_at', '>=', $from)
            ->where('status', '!=', 'cancelled')
            ->select('city', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as revenue'))
            ->groupBy('city')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        return [
            'period'                 => $days,
            'totalRevenue'           => $totalRevenue,
            'totalOrders'            => $totalOrders,
            'avgOrderValue'          => $avgOrderValue,
            'paidOrders'             => $paidOrders,
            'unpaidOrders'           => $unpaidOrders,
            'codOrders'              => $codOrders,
            'onlineOrders'           => $onlineOrders,
            'dailyRevenue'           => $dailyRevenue,
            'statusBreakdown'        => $statusBreakdown,
            'paymentMethodBreakdown' => $paymentMethodBreakdown,
            'topProducts'            => $topProducts,
            'topCities'              => $topCities,
        ];
    }
}
