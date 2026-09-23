<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComboResource\Pages;
use App\Models\Combo;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ComboResource extends Resource
{
    protected static ?string $model = Combo::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationLabel = 'Combo Packages';

    protected static ?string $modelLabel = 'Combo Package';

    protected static ?string $pluralModelLabel = 'Combo Packages';

    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('is_active', true)->count();
        return $count ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ── Basic Info ───────────────────────────────────────────
                Forms\Components\Section::make('Combo Package Information')
                    ->description('Set package title, URL slug, badges, and marketing descriptions')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Combo Name')
                            ->placeholder('e.g. Ramadan Special Dates & Honey Pack')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),

                        Forms\Components\TextInput::make('slug')
                            ->label('URL Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('badge')
                            ->label('Badge / Ribbon Text')
                            ->placeholder('e.g. Mega Deal, Best Seller, Save 15%')
                            ->datalist([
                                'Best Seller',
                                'Mega Deal',
                                'Save 15%',
                                'Save 20%',
                                'Immunity Boost',
                                'Kitchen Pack',
                                'Gift Hamper',
                                'Limited Offer',
                            ]),

                        Forms\Components\Textarea::make('short_description')
                            ->label('Short Description')
                            ->placeholder('Brief overview of what makes this combo special...')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('description')
                            ->label('Detailed Description')
                            ->placeholder('Full details, benefits, purity guarantees, and storage tips...')
                            ->columnSpanFull(),
                    ])->columns(3),

                // ── Combo Items Repeater ─────────────────────────────────
                Forms\Components\Section::make('Included Products in this Combo')
                    ->description('Select the products and quantities that comprise this bundle package')
                    ->icon('heroicon-o-shopping-cart')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Forms\Components\Grid::make(12)
                                    ->schema([
                                        Forms\Components\Select::make('product_id')
                                            ->label('Select Product')
                                            ->options(fn () => Product::query()->orderBy('name')->pluck('name', 'id'))
                                            ->searchable()
                                            ->required()
                                            ->live()
                                            ->columnSpan(7),

                                        Forms\Components\TextInput::make('quantity')
                                            ->label('Quantity')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->required()
                                            ->columnSpan(2),

                                        Forms\Components\TextInput::make('custom_name')
                                            ->label('Custom Label (Optional)')
                                            ->placeholder('e.g. 500g Jar')
                                            ->maxLength(255)
                                            ->columnSpan(3),
                                    ]),
                            ])
                            ->itemLabel(function (array $state): ?string {
                                $product = ! empty($state['product_id']) ? Product::find($state['product_id']) : null;
                                if (! $product) {
                                    return null;
                                }
                                $qty = ! empty($state['quantity']) && (int) $state['quantity'] > 1 ? "{$state['quantity']}x " : '';
                                $label = ! empty($state['custom_name']) ? $state['custom_name'] : $product->name;
                                return "{$qty}{$label} (Regular: ৳" . number_format($product->price) . ")";
                            })
                            ->addActionLabel('Add Product to Combo')
                            ->reorderable()
                            ->collapsible()
                            ->cloneable()
                            ->defaultItems(2)
                            ->columnSpanFull(),
                    ]),

                // ── Pricing & Inventory ──────────────────────────────────
                Forms\Components\Section::make('Pricing & Inventory')
                    ->description('Set regular price, special combo offer price, and inventory count')
                    ->icon('heroicon-o-currency-bangladeshi')
                    ->schema([
                        Forms\Components\TextInput::make('original_price')
                            ->label('Regular Price (Sum of Items)')
                            ->prefix('৳')
                            ->numeric()
                            ->placeholder('e.g. 3500')
                            ->helperText('Sum of individual items if bought separately'),

                        Forms\Components\TextInput::make('price')
                            ->label('Combo Offer Price')
                            ->prefix('৳')
                            ->numeric()
                            ->required()
                            ->placeholder('e.g. 2950')
                            ->helperText('Discounted package price the customer actually pays'),

                        Forms\Components\TextInput::make('stock')
                            ->label('Available Stock')
                            ->numeric()
                            ->default(100)
                            ->required()
                            ->helperText('Number of combo packages ready for purchase'),
                    ])->columns(3),

                // ── Media & Display Settings ─────────────────────────────
                Forms\Components\Section::make('Media & Visibility')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Combo Package Banner / Photo')
                            ->disk('public')
                            ->directory('combos')
                            ->visibility('public')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/jpg'])
                            ->maxSize(51200)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active for Sale')
                            ->default(true)
                            ->inline(false)
                            ->helperText('Visible and purchasable in store'),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Feature on Homepage')
                            ->default(true)
                            ->inline(false)
                            ->helperText('Showcase in the Exclusive Combo Deals band on homepage'),

                        Forms\Components\TextInput::make('sort')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Photo')
                    ->square()
                    ->size(48)
                    ->defaultImageUrl(asset('images/placeholder.svg')),

                Tables\Columns\TextColumn::make('name')
                    ->label('Combo Package')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Combo $record): string => $record->items_summary),

                Tables\Columns\TextColumn::make('price')
                    ->label('Combo Price')
                    ->formatStateUsing(fn ($state) => '৳' . number_format($state))
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('original_price')
                    ->label('Regular Price')
                    ->formatStateUsing(fn ($state) => $state ? '৳' . number_format($state) : '—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('savings')
                    ->label('Savings')
                    ->state(function (Combo $record): string {
                        if ($record->savings_amount > 0) {
                            return 'Save ৳' . number_format($record->savings_amount) . ' (' . $record->savings_percent . '%)';
                        }
                        return '—';
                    })
                    ->badge()
                    ->color(fn (Combo $record) => $record->savings_amount > 0 ? 'warning' : 'gray'),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger')),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort')
                    ->label('Order')
                    ->sortable(),
            ])
            ->defaultSort('sort')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Homepage Featured'),
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCombos::route('/'),
            'create' => Pages\CreateCombo::route('/create'),
            'edit'   => Pages\EditCombo::route('/{record}/edit'),
        ];
    }
}
