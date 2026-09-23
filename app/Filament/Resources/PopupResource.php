<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PopupResource\Pages;
use App\Models\Popup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PopupResource extends Resource
{
    protected static ?string $model = Popup::class;

    protected static ?string $navigationIcon = 'heroicon-o-window';

    protected static ?string $navigationGroup = 'CMS';

    protected static ?int $navigationSort = 24;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Content')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\RichEditor::make('content')
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('popups')
                            ->maxSize(20480),

                        Forms\Components\TextInput::make('button_text')
                            ->placeholder('e.g. Shop Now')
                            ->maxLength(50),

                        Forms\Components\TextInput::make('button_url')
                            ->placeholder('e.g. /shop')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Trigger & Schedule')
                    ->schema([
                        Forms\Components\Select::make('trigger')
                            ->options([
                                'on_load'     => 'On Page Load',
                                'on_exit'     => 'On Exit Intent',
                                'after_delay' => 'After Delay',
                            ])
                            ->default('on_load')
                            ->required(),

                        Forms\Components\TextInput::make('delay_seconds')
                            ->label('Delay (seconds)')
                            ->numeric()
                            ->default(0)
                            ->visible(fn (Forms\Get $get) => $get('trigger') === 'after_delay'),

                        Forms\Components\DatePicker::make('start_date')
                            ->placeholder('Immediately'),

                        Forms\Components\DatePicker::make('end_date')
                            ->placeholder('No end date'),

                        Forms\Components\Toggle::make('is_active')
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('trigger')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'on_load'     => 'On Load',
                        'on_exit'     => 'Exit Intent',
                        'after_delay' => 'After Delay',
                        default       => $state,
                    }),

                Tables\Columns\TextColumn::make('start_date')
                    ->date('d M Y')
                    ->placeholder('Always'),

                Tables\Columns\TextColumn::make('end_date')
                    ->date('d M Y')
                    ->placeholder('Never'),

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
            'index'  => Pages\ListPopups::route('/'),
            'create' => Pages\CreatePopup::route('/create'),
            'edit'   => Pages\EditPopup::route('/{record}/edit'),
        ];
    }
}
