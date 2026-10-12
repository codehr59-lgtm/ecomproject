<?php

namespace App\Filament\Resources\BannerResource\Pages;

use App\Filament\Resources\BannerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBanners extends ListRecords
{
    protected static string $resource = BannerResource::class;

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
