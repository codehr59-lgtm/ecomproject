<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';


    protected static ?string $navigationLabel = 'All Products';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ── Product Type ─────────────────────────────────────────
                Forms\Components\Section::make('Product Type')
                    ->description('Select product type first')
                    ->schema([
                        Forms\Components\ToggleButtons::make('product_type')
                            ->options([
                                'simple'   => 'Simple Product',
                                'variable' => 'Variable Product',
                            ])
                            ->icons([
                                'simple'   => 'heroicon-o-cube',
                                'variable' => 'heroicon-o-squares-2x2',
                            ])
                            ->colors([
                                'simple'   => 'success',
                                'variable' => 'warning',
                            ])
                            ->default('simple')
                            ->required()
                            ->inline()
                            ->live()
                            ->columnSpanFull(),
                    ]),

                // ── Basic Info ───────────────────────────────────────────
                Forms\Components\Section::make('Basic Info')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->maxLength(100)
                            ->placeholder('e.g. SHV-HON-001'),
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->options(function () {
                                $options = [];
                                $parents = Category::whereNull('parent_id')->with('children')->orderBy('sort')->get();
                                foreach ($parents as $parent) {
                                    $options[$parent->name] = [$parent->id => $parent->name];
                                    foreach ($parent->children as $child) {
                                        $options[$parent->name][$child->id] = '↳ ' . $child->name;
                                    }
                                }
                                return $options;
                            })
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('brand_id')
                            ->label('Brand')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('weight')
                            ->maxLength(100),
                        Forms\Components\Select::make('badge')
                            ->options([
                                'best'     => 'Best',
                                'new'      => 'New',
                                'preorder' => 'Pre-order',
                                ''         => 'None',
                            ])
                            ->placeholder('None'),
                        Forms\Components\Select::make('tags')
                            ->label('Tags')
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(100)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(100)
                                    ->unique('tags', 'slug'),
                            ]),
                    ])->columns(2),

                // ── Simple Product: Price & Stock ─────────────────────────
                Forms\Components\Section::make('Price & Stock')
                    ->description('Set price and inventory for this product')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->prefix('৳')
                            ->required(fn (Get $get): bool => $get('product_type') !== 'variable'),
                        Forms\Components\TextInput::make('old_price')
                            ->numeric()
                            ->prefix('৳')
                            ->label('Compare at Price'),
                        Forms\Components\TextInput::make('stock')
                            ->numeric()
                            ->required(fn (Get $get): bool => $get('product_type') !== 'variable')
                            ->default(0),
                    ])->columns(3)
                    ->visible(fn (Get $get): bool => $get('product_type') !== 'variable'),

                // ── Variable Product: Variations ─────────────────────────
                Forms\Components\Section::make('Product Variations')
                    ->description('Add each variation with its own price, stock & image')
                    ->icon('heroicon-o-squares-2x2')
                    ->schema([
                        Forms\Components\Repeater::make('variations')
                            ->relationship()
                            ->schema([
                                Forms\Components\Grid::make(4)
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
                                            ->placeholder('e.g. 500g, Red, XL'),
                                        Forms\Components\TextInput::make('price')
                                            ->numeric()
                                            ->required()
                                            ->prefix('৳')
                                            ->placeholder('0'),
                                        Forms\Components\TextInput::make('stock')
                                            ->numeric()
                                            ->required()
                                            ->default(0)
                                            ->placeholder('0'),
                                    ]),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('sku')
                                            ->label('SKU')
                                            ->maxLength(100)
                                            ->placeholder('Optional SKU'),
                                        Forms\Components\FileUpload::make('image')
                                            ->disk('public')
                                            ->directory('products/variations')
                                            ->visibility('public')
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/jpg'])
                                            ->maxSize(51200),
                                    ]),
                            ])
                            ->itemLabel(fn (array $state): ?string =>
                                ($state['type'] ?? '') && ($state['label'] ?? '')
                                    ? ucfirst($state['type']) . ': ' . $state['label'] . ($state['price'] ? ' — ৳' . $state['price'] : '')
                                    : null
                            )
                            ->addActionLabel('Add Variation')
                            ->reorderable()
                            ->collapsible()
                            ->cloneable()
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Get $get): bool => $get('product_type') === 'variable'),

                // ── Main Image & Video ──────────────────────────────────
                Forms\Components\Section::make('Main Image & Video')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->disk('public')
                            ->directory('products')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/jpg'])
                            ->maxSize(51200),
                        Forms\Components\TextInput::make('video_url')
                            ->label('Product Video URL')
                            ->placeholder('https://www.youtube.com/watch?v=...')
                            ->url()
                            ->maxLength(500),
                    ])->columns(2),

                // ── Gallery Images ───────────────────────────────────────
                Forms\Components\Section::make('Gallery Images')
                    ->description('Select multiple images at once to upload')
                    ->schema([
                        Forms\Components\FileUpload::make('gallery')
                            ->disk('public')
                            ->directory('products/gallery')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/jpg'])
                            ->maxSize(51200)
                            ->multiple()
                            ->reorderable()
                            ->maxFiles(20)
                            ->columnSpanFull(),
                    ]),

                // ── Details ──────────────────────────────────────────────
                Forms\Components\Section::make('Details')
                    ->schema([
                        Forms\Components\Textarea::make('blurb')
                            ->label('Short Description')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('description')
                            ->label('Full Description')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('rating')
                            ->numeric()
                            ->step(0.1)
                            ->minValue(0)
                            ->maxValue(5),
                        Forms\Components\TextInput::make('reviews')
                            ->numeric()
                            ->label('Review Count'),
                        Forms\Components\Toggle::make('certified')
                            ->label('Certified'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),

                // ── Specifications ──────────────────────────────────────
                Forms\Components\Section::make('Specifications')
                    ->description('Add product specifications like weight, material, dimensions, etc.')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        Forms\Components\Repeater::make('specifications')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('e.g. Material, Dimensions, Origin'),
                                Forms\Components\TextInput::make('value')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. 100% Cotton, 10×20cm, Bangladesh'),
                            ])
                            ->columns(2)
                            ->addActionLabel('Add Specification')
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                // ── FAQs ────────────────────────────────────────────────
                Forms\Components\Section::make('FAQs')
                    ->description('Frequently asked questions about this product')
                    ->icon('heroicon-o-question-mark-circle')
                    ->schema([
                        Forms\Components\Repeater::make('faqs')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('question')
                                    ->required()
                                    ->maxLength(500)
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('answer')
                                    ->required()
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->addActionLabel('Add FAQ')
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                // ── Related Products ────────────────────────────────────
                Forms\Components\Section::make('Related Products')
                    ->description('Link products that customers might also be interested in')
                    ->icon('heroicon-o-link')
                    ->schema([
                        Forms\Components\Select::make('relatedProducts')
                            ->label('Select Related Products')
                            ->relationship('relatedProducts', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->width(50)
                    ->height(50),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('product_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'variable' ? 'warning' : 'success')
                    ->formatStateUsing(fn (string $state): string => $state === 'variable' ? 'Variable' : 'Simple'),
                Tables\Columns\TextColumn::make('price')
                    ->money('BDT')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->sortable()
                    ->color(fn (Product $record): string => $record->stock < 10 ? 'danger' : 'success'),
                Tables\Columns\BadgeColumn::make('badge')
                    ->colors([
                        'warning' => 'best',
                        'success' => 'new',
                        'primary' => 'preorder',
                    ]),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product_type')
                    ->label('Type')
                    ->options([
                        'simple'   => 'Simple',
                        'variable' => 'Variable',
                    ]),
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\Action::make('view_live')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (Product $record): string => url("/product/{$record->id}"))
                    ->openUrlInNewTab(),
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
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
