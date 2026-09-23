<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'CMS';

    protected static ?int $navigationSort = 23;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options([
                                'slider' => 'Slider (Left - carousel)',
                                'banner' => 'Banner (Right - static)',
                            ])
                            ->default('slider')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->required()
                            ->directory('sliders')
                            ->visibility('public')
                            ->maxSize(30720)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('title')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('subtitle')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('button_text')
                            ->placeholder('e.g. Shop Now')
                            ->maxLength(50),

                        Forms\Components\TextInput::make('button_url')
                            ->placeholder('e.g. /shop')
                            ->maxLength(255),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true),

                        Forms\Components\TextInput::make('sort')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->height(60),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->placeholder('(no title)'),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'slider',
                        'success' => 'banner',
                    ]),

                Tables\Columns\TextColumn::make('button_text')
                    ->label('CTA')
                    ->placeholder('—'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
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
            'index'  => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit'   => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
