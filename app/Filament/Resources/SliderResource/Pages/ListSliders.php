<?php

namespace App\Filament\Resources\SliderResource\Pages;

use App\Filament\Resources\SliderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSliders extends ListRecords
{
    protected static string $resource = SliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('sizing_settings')
                ->label('Slider & Banner Sizing')
                ->icon('heroicon-o-arrows-pointing-out')
                ->color('success')
                ->url(url('/admin/homepage-settings')),
            Actions\CreateAction::make(),
        ];
    }
}
