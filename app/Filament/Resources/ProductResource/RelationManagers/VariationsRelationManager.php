<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class VariationsRelationManager extends RelationManager
{
    protected static string $relationship = 'variations';

    protected static ?string $title = 'Variations';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->product_type === 'variable';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options([
                                'size'   => 'Size',
                                'color'  => 'Color',
                                'weight' => 'Weight',
                                'pack'   => 'Pack',
                            ])
                            ->required()
                            ->default('size'),
                        Forms\Components\TextInput::make('label')
                            ->required()
                            ->maxLength(255)
                            ->helperText('e.g. 500g, Red, Large, 6-Pack'),
                    ])->columns(2),
                Forms\Components\Section::make('Price & Stock')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->required()
                            ->prefix('৳'),
                        Forms\Components\TextInput::make('stock')
                            ->numeric()
                            ->required()
                            ->default(0),
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->maxLength(100),
                    ])->columns(3),
                Forms\Components\Section::make('Variation Image')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->disk('public')
                            ->directory('products/variations')
                            ->image()
                            ->imageResizeMode('cover'),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->width(50)
                    ->height(50),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'size'   => 'info',
                        'color'  => 'success',
                        'weight' => 'warning',
                        'pack'   => 'primary',
                        default  => 'gray',
                    }),
                Tables\Columns\TextColumn::make('label')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('BDT')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->sortable()
                    ->color(fn (int $state): string => $state < 10 ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU'),
            ])
            ->defaultSort('type')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Variation'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
