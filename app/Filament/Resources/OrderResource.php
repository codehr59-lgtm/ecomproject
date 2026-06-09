<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Orders';

    // ── Form (edit only: status, payment_status, courier fields) ─────────

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending'    => 'Pending',
                                'confirmed'  => 'Confirmed',
                                'processing' => 'Processing',
                                'shipped'    => 'Shipped',
                                'delivered'  => 'Delivered',
                                'cancelled'  => 'Cancelled',
                            ])
                            ->required(),
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'unpaid' => 'Unpaid',
                                'paid'   => 'Paid',
                                'failed' => 'Failed',
                            ])
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Courier')
                    ->schema([
                        Forms\Components\TextInput::make('courier')
                            ->placeholder('e.g. Steadfast'),
                        Forms\Components\TextInput::make('courier_tracking')
                            ->label('Tracking Number'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Customer Info')
                    ->schema([
                        Forms\Components\TextInput::make('customer_name')->disabled(),
                        Forms\Components\TextInput::make('customer_phone')->disabled(),
                        Forms\Components\TextInput::make('customer_email')->disabled(),
                        Forms\Components\TextInput::make('address_line')->disabled(),
                        Forms\Components\TextInput::make('city')->disabled(),
                        Forms\Components\TextInput::make('thana')->disabled(),
                        Forms\Components\Textarea::make('notes')->disabled()->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    // ── Table ─────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('placed_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->searchable()
                    ->sortable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold)
                    ->copyable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total')
                    ->formatStateUsing(fn ($state) => '৳' . number_format($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'    => 'warning',
                        'confirmed'  => 'primary',
                        'processing' => 'info',
                        'shipped'    => 'success',
                        'delivered'  => 'success',
                        'cancelled'  => 'danger',
                        default      => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Payment')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'unpaid' => 'warning',
                        'paid'   => 'success',
                        'failed' => 'danger',
                        default  => 'gray',
                    }),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Method')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('placed_at')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'    => 'Pending',
                        'confirmed'  => 'Confirmed',
                        'processing' => 'Processing',
                        'shipped'    => 'Shipped',
                        'delivered'  => 'Delivered',
                        'cancelled'  => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'paid'   => 'Paid',
                        'failed' => 'Failed',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        'cod'        => 'Cash on Delivery',
                        'bkash'      => 'bKash',
                        'sslcommerz' => 'SSLCommerz',
                    ]),
            ])
            ->actions([
                // Status change
                Tables\Actions\Action::make('updateStatus')
                    ->label('Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending'    => 'Pending',
                                'confirmed'  => 'Confirmed',
                                'processing' => 'Processing',
                                'shipped'    => 'Shipped',
                                'delivered'  => 'Delivered',
                                'cancelled'  => 'Cancelled',
                            ])
                            ->required(),
                    ])
                    ->action(function (Order $record, array $data) {
                        $record->update(['status' => $data['status']]);
                    }),

                // Invoice link
                Tables\Actions\Action::make('invoice')
                    ->label('Invoice')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('gray')
                    ->url(fn (Order $record): string => route('order.invoice', $record->number))
                    ->openUrlInNewTab(),

                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // ── Infolist (View page) ──────────────────────────────────────────────

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Order Overview')
                    ->schema([
                        Infolists\Components\TextEntry::make('number')
                            ->weight(\Filament\Support\Enums\FontWeight::Bold),
                        Infolists\Components\TextEntry::make('status')
                            ->badge(),
                        Infolists\Components\TextEntry::make('payment_method')
                            ->label('Payment Method'),
                        Infolists\Components\TextEntry::make('payment_status')
                            ->label('Payment Status')
                            ->badge(),
                        Infolists\Components\TextEntry::make('payment_ref')
                            ->label('Payment Ref')
                            ->placeholder('—')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('placed_at')
                            ->dateTime('d M Y, h:i A'),
                        Infolists\Components\TextEntry::make('total')
                            ->formatStateUsing(fn ($state) => '৳' . number_format($state)),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Customer')
                    ->schema([
                        Infolists\Components\TextEntry::make('customer_name'),
                        Infolists\Components\TextEntry::make('customer_phone'),
                        Infolists\Components\TextEntry::make('customer_email'),
                        Infolists\Components\TextEntry::make('address_line'),
                        Infolists\Components\TextEntry::make('city'),
                        Infolists\Components\TextEntry::make('thana'),
                        Infolists\Components\TextEntry::make('notes')->columnSpanFull(),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Financials')
                    ->schema([
                        Infolists\Components\TextEntry::make('subtotal')
                            ->formatStateUsing(fn ($state) => '৳' . number_format($state)),
                        Infolists\Components\TextEntry::make('delivery')
                            ->formatStateUsing(fn ($state) => $state === 0 ? 'Free' : '৳' . number_format($state)),
                        Infolists\Components\TextEntry::make('discount')
                            ->formatStateUsing(fn ($state) => $state > 0 ? '−৳' . number_format($state) : '—'),
                        Infolists\Components\TextEntry::make('coupon_code')
                            ->placeholder('None'),
                    ])
                    ->columns(4),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
            'view'   => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
