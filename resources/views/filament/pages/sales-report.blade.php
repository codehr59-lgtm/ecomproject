<x-filament-panels::page>

    {{-- Period Selector --}}
    <div style="display:flex; gap:8px; margin-bottom:16px;">
        @foreach([7 => '7 Days', 30 => '30 Days', 90 => '90 Days', 365 => '1 Year'] as $d => $label)
            <a href="?period={{ $d }}"
               style="padding:6px 16px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none;
                      {{ (int)$period === $d ? 'background:rgb(251,191,36); color:#000;' : 'background:rgba(255,255,255,0.05); color:inherit; border:1px solid rgba(0,0,0,0.1);' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Summary Cards --}}
    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; margin-bottom:24px;">
        <x-filament::section>
            <div style="text-align:center;">
                <div style="font-size:13px; color:gray; margin-bottom:4px;">Total Revenue</div>
                <div style="font-size:24px; font-weight:800;">৳{{ number_format($totalRevenue) }}</div>
            </div>
        </x-filament::section>
        <x-filament::section>
            <div style="text-align:center;">
                <div style="font-size:13px; color:gray; margin-bottom:4px;">Total Orders</div>
                <div style="font-size:24px; font-weight:800;">{{ number_format($totalOrders) }}</div>
            </div>
        </x-filament::section>
        <x-filament::section>
            <div style="text-align:center;">
                <div style="font-size:13px; color:gray; margin-bottom:4px;">Avg Order Value</div>
                <div style="font-size:24px; font-weight:800;">৳{{ number_format($avgOrderValue) }}</div>
            </div>
        </x-filament::section>
        <x-filament::section>
            <div style="text-align:center;">
                <div style="font-size:13px; color:gray; margin-bottom:4px;">Paid / Unpaid</div>
                <div style="font-size:24px; font-weight:800;">{{ $paidOrders }} / {{ $unpaidOrders }}</div>
            </div>
        </x-filament::section>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:24px;">

        {{-- Order Status Breakdown --}}
        <x-filament::section heading="Order Status Breakdown">
            <table style="width:100%; font-size:14px;">
                <thead>
                    <tr style="text-align:left; border-bottom:1px solid rgba(0,0,0,0.1);">
                        <th style="padding:8px 0;">Status</th>
                        <th style="padding:8px 0;">Orders</th>
                        <th style="padding:8px 0;">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s)
                        @if(isset($statusBreakdown[$s]))
                        <tr style="border-bottom:1px solid rgba(0,0,0,0.05);">
                            <td style="padding:8px 0; text-transform:capitalize;">{{ $s }}</td>
                            <td style="padding:8px 0;">{{ $statusBreakdown[$s]->count }}</td>
                            <td style="padding:8px 0;">৳{{ number_format($statusBreakdown[$s]->revenue) }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </x-filament::section>

        {{-- Payment Method Breakdown --}}
        <x-filament::section heading="Payment Methods">
            <table style="width:100%; font-size:14px;">
                <thead>
                    <tr style="text-align:left; border-bottom:1px solid rgba(0,0,0,0.1);">
                        <th style="padding:8px 0;">Method</th>
                        <th style="padding:8px 0;">Orders</th>
                        <th style="padding:8px 0;">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paymentMethodBreakdown as $pm)
                    <tr style="border-bottom:1px solid rgba(0,0,0,0.05);">
                        <td style="padding:8px 0; text-transform:uppercase;">{{ $pm->payment_method }}</td>
                        <td style="padding:8px 0;">{{ $pm->count }}</td>
                        <td style="padding:8px 0;">৳{{ number_format($pm->revenue) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </x-filament::section>
    </div>

    {{-- Top Products --}}
    <x-filament::section heading="Top 10 Products" style="margin-bottom:24px;">
        <table style="width:100%; font-size:14px;">
            <thead>
                <tr style="text-align:left; border-bottom:1px solid rgba(0,0,0,0.1);">
                    <th style="padding:8px 0;">#</th>
                    <th style="padding:8px 0;">Product</th>
                    <th style="padding:8px 0;">Qty Sold</th>
                    <th style="padding:8px 0;">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProducts as $i => $p)
                <tr style="border-bottom:1px solid rgba(0,0,0,0.05);">
                    <td style="padding:8px 0;">{{ $i + 1 }}</td>
                    <td style="padding:8px 0;">{{ $p->name }}</td>
                    <td style="padding:8px 0;">{{ $p->total_qty }}</td>
                    <td style="padding:8px 0;">৳{{ number_format($p->total_revenue) }}</td>
                </tr>
                @endforeach
                @if($topProducts->isEmpty())
                <tr><td colspan="4" style="padding:16px 0; text-align:center; color:gray;">No sales data for this period.</td></tr>
                @endif
            </tbody>
        </table>
    </x-filament::section>

    {{-- Top Cities --}}
    <x-filament::section heading="Top Cities">
        <table style="width:100%; font-size:14px;">
            <thead>
                <tr style="text-align:left; border-bottom:1px solid rgba(0,0,0,0.1);">
                    <th style="padding:8px 0;">#</th>
                    <th style="padding:8px 0;">City</th>
                    <th style="padding:8px 0;">Orders</th>
                    <th style="padding:8px 0;">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topCities as $i => $c)
                <tr style="border-bottom:1px solid rgba(0,0,0,0.05);">
                    <td style="padding:8px 0;">{{ $i + 1 }}</td>
                    <td style="padding:8px 0;">{{ $c->city }}</td>
                    <td style="padding:8px 0;">{{ $c->count }}</td>
                    <td style="padding:8px 0;">৳{{ number_format($c->revenue) }}</td>
                </tr>
                @endforeach
                @if($topCities->isEmpty())
                <tr><td colspan="4" style="padding:16px 0; text-align:center; color:gray;">No data for this period.</td></tr>
                @endif
            </tbody>
        </table>
    </x-filament::section>

    {{-- Daily Revenue Table --}}
    <x-filament::section heading="Daily Revenue" style="margin-top:24px;">
        <div style="max-height:400px; overflow-y:auto;">
            <table style="width:100%; font-size:14px;">
                <thead>
                    <tr style="text-align:left; border-bottom:1px solid rgba(0,0,0,0.1);">
                        <th style="padding:8px 0;">Date</th>
                        <th style="padding:8px 0;">Orders</th>
                        <th style="padding:8px 0;">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyRevenue->reverse() as $day)
                    <tr style="border-bottom:1px solid rgba(0,0,0,0.05);">
                        <td style="padding:8px 0;">{{ \Carbon\Carbon::parse($day->date)->format('d M Y') }}</td>
                        <td style="padding:8px 0;">{{ $day->count }}</td>
                        <td style="padding:8px 0;">৳{{ number_format($day->revenue) }}</td>
                    </tr>
                    @endforeach
                    @if($dailyRevenue->isEmpty())
                    <tr><td colspan="3" style="padding:16px 0; text-align:center; color:gray;">No sales data for this period.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </x-filament::section>

</x-filament-panels::page>
