<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Gallery Images';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('path')
                    ->disk('public')
                    ->directory('products/gallery')
                    ->image()
                    ->imageResizeMode('cover')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('sort')
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower number = shown first'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('path')
            ->reorderable('sort')
            ->columns([
                Tables\Columns\ImageColumn::make('path')
                    ->label('Image')
                    ->disk('public')
                    ->width(100)
                    ->height(80),
                Tables\Columns\TextColumn::make('sort')
                    ->label('Order')
                    ->sortable(),
            ])
            ->defaultSort('sort')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Image'),
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
