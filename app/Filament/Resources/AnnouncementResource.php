<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Models\Announcement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Announcements';

    protected static ?int $navigationSort = 25;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('text')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Free shipping on orders over ৳1,500!')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('link')
                            ->url()
                            ->placeholder('e.g. /shop')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('link_text')
                            ->placeholder('e.g. Shop Now')
                            ->maxLength(50),

                        Forms\Components\ColorPicker::make('bg_color')
                            ->label('Background Color')
                            ->default('#2E7D32'),

                        Forms\Components\ColorPicker::make('text_color')
                            ->label('Text Color')
                            ->default('#FFFFFF'),

                        Forms\Components\Toggle::make('is_dismissible')
                            ->default(true),

                        Forms\Components\Toggle::make('is_active')
                            ->default(false),

                        Forms\Components\DatePicker::make('start_date')
                            ->placeholder('Immediately'),

                        Forms\Components\DatePicker::make('end_date')
                            ->placeholder('No end date'),

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
                Tables\Columns\TextColumn::make('text')
                    ->searchable()
                    ->limit(60),

                Tables\Columns\ColorColumn::make('bg_color')
                    ->label('Color'),

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
            'index'  => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit'   => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
