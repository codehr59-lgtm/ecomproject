<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Models\Menu;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Menu Builder';

    protected static ?int $navigationSort = 26;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Menu')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Main Navigation'),

                        Forms\Components\Select::make('location')
                            ->options([
                                'header'  => 'Header',
                                'footer'  => 'Footer',
                                'mobile'  => 'Mobile',
                                'sidebar' => 'Sidebar',
                            ])
                            ->required()
                            ->unique(ignoreRecord: true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Menu Items')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->required()
                                    ->maxLength(100),

                                Forms\Components\Select::make('type')
                                    ->options([
                                        'custom'   => 'Custom URL',
                                        'page'     => 'CMS Page',
                                        'category' => 'Category',
                                    ])
                                    ->default('custom')
                                    ->live(),

                                Forms\Components\TextInput::make('url')
                                    ->placeholder('/shop')
                                    ->maxLength(255)
                                    ->visible(fn (Forms\Get $get) => $get('type') === 'custom'),

                                Forms\Components\Select::make('reference_id')
                                    ->label(fn (Forms\Get $get) => $get('type') === 'page' ? 'Page' : 'Category')
                                    ->options(fn (Forms\Get $get) => match ($get('type')) {
                                        'page'     => \App\Models\Page::published()->pluck('title', 'id'),
                                        'category' => \App\Models\Category::pluck('name', 'id'),
                                        default    => [],
                                    })
                                    ->searchable()
                                    ->visible(fn (Forms\Get $get) => in_array($get('type'), ['page', 'category'])),

                                Forms\Components\Select::make('target')
                                    ->options([
                                        '_self'  => 'Same Tab',
                                        '_blank' => 'New Tab',
                                    ])
                                    ->default('_self'),

                                Forms\Components\TextInput::make('icon')
                                    ->placeholder('heroicon-o-home')
                                    ->maxLength(100),

                                Forms\Components\Toggle::make('is_active')
                                    ->default(true),
                            ])
                            ->columns(3)
                            ->reorderable()
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->defaultItems(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('location')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit'   => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
