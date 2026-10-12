<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'CMS';

    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('sizing_tip')
                    ->label('Banner Placement Guide')
                    ->content(new \Illuminate\Support\HtmlString('💡 <strong>Where will this banner appear?</strong><br>• <strong>Hero Banner:</strong> Appears at the very top of the homepage beside the slider.<br>• <strong>Middle Strip Banner:</strong> Appears as the full-width promotional banner between product sections (e.g. <em>"Sourced with care, delivered with trust"</em>). You can customize image, title, subtitle, CTA button & link!'))
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('title')
                    ->label('Banner Title')
                    ->placeholder('e.g. Sourced with care, delivered with trust')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('subtitle')
                    ->label('Subtitle')
                    ->placeholder('e.g. 100% Pure, Organic & Halal Groceries Delivered Direct to Your Door')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->label('Banner Image / Background')
                    ->disk('public')
                    ->directory('banners')
                    ->image()
                    ->helperText('Recommended: Wide landscape photo (e.g. 1200x300 or 1400x350)'),
                Forms\Components\TextInput::make('cta')
                    ->label('CTA Button Text')
                    ->placeholder('e.g. Order Today or Explore Products')
                    ->maxLength(100),
                Forms\Components\TextInput::make('href')
                    ->label('Link URL')
                    ->placeholder('e.g. /shop or https://...')
                    ->maxLength(500),
                Forms\Components\Select::make('position')
                    ->label('Banner Position / Section')
                    ->options([
                        'hero'  => 'Hero Banner (Top beside slider)',
                        'strip' => 'Middle Strip Banner (Section between product rails — e.g. "Sourced with care")',
                        'promo' => 'Promo Banner',
                    ])
                    ->default('hero')
                    ->required()
                    ->helperText('Select "Middle Strip Banner" to manage the full-width banner section on the homepage.'),
                Forms\Components\TextInput::make('sort')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->width(80)
                    ->height(50),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('position')
                    ->colors([
                        'primary' => 'hero',
                        'warning' => 'strip',
                        'success' => 'promo',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'hero'  => 'Hero Banner',
                        'strip' => 'Middle Strip Banner',
                        'promo' => 'Promo Banner',
                        default => ucfirst($state),
                    }),
                Tables\Columns\TextColumn::make('sort')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('position')
                    ->options([
                        'hero'  => 'Hero Banner',
                        'strip' => 'Middle Strip Banner',
                        'promo' => 'Promo Banner',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active'),
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

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit'   => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
