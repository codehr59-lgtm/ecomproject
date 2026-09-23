<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReturnRequestResource\Pages;
use App\Models\ReturnRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReturnRequestResource extends Resource
{
    protected static ?string $model = ReturnRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';

    protected static ?string $navigationLabel = 'Returns & Refunds';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Request Details')
                    ->schema([
                        Forms\Components\Select::make('order_id')
                            ->label('Order')
                            ->relationship('order', 'number')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('type')
                            ->options([
                                'return'   => 'Return',
                                'exchange' => 'Exchange',
                                'refund'   => 'Refund',
                            ])
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'pending'   => 'Pending',
                                'approved'  => 'Approved',
                                'rejected'  => 'Rejected',
                                'completed' => 'Completed',
                            ])
                            ->default('pending')
                            ->required(),

                        Forms\Components\TextInput::make('reason')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('details')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Resolution')
                    ->schema([
                        Forms\Components\TextInput::make('refund_amount')
                            ->numeric()
                            ->prefix('৳')
                            ->placeholder('Amount to refund (if applicable)'),

                        Forms\Components\TextInput::make('resolution_note')
                            ->maxLength(255)
                            ->placeholder('Admin resolution note'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('order.number')
                    ->label('Order')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'return'   => 'warning',
                        'exchange' => 'info',
                        'refund'   => 'danger',
                        default    => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'   => 'warning',
                        'approved'  => 'primary',
                        'rejected'  => 'danger',
                        'completed' => 'success',
                        default     => 'gray',
                    }),

                Tables\Columns\TextColumn::make('reason')
                    ->limit(40),

                Tables\Columns\TextColumn::make('refund_amount')
                    ->label('Refund')
                    ->formatStateUsing(fn ($state) => $state ? '৳' . number_format($state) : '—'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Requested')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'return'   => 'Return',
                        'exchange' => 'Exchange',
                        'refund'   => 'Refund',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'approved'  => 'Approved',
                        'rejected'  => 'Rejected',
                        'completed' => 'Completed',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (ReturnRequest $record) => $record->status === 'pending')
                    ->action(function (ReturnRequest $record) {
                        $record->update([
                            'status'      => 'approved',
                            'resolved_by' => auth()->user()?->name,
                            'resolved_at' => now(),
                        ]);
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (ReturnRequest $record) => $record->status === 'pending')
                    ->form([
                        Forms\Components\TextInput::make('resolution_note')
                            ->label('Rejection Reason')
                            ->required(),
                    ])
                    ->action(function (ReturnRequest $record, array $data) {
                        $record->update([
                            'status'          => 'rejected',
                            'resolution_note' => $data['resolution_note'],
                            'resolved_by'     => auth()->user()?->name,
                            'resolved_at'     => now(),
                        ]);
                    }),

                Tables\Actions\Action::make('complete')
                    ->label('Complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (ReturnRequest $record) => $record->status === 'approved')
                    ->action(function (ReturnRequest $record) {
                        $record->update([
                            'status'      => 'completed',
                            'resolved_by' => auth()->user()?->name,
                            'resolved_at' => now(),
                        ]);
                    }),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListReturnRequests::route('/'),
            'create' => Pages\CreateReturnRequest::route('/create'),
            'edit'   => Pages\EditReturnRequest::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = ReturnRequest::where('status', 'pending')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
