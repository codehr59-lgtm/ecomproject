<?php

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Resources\AnnouncementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnnouncements extends ListRecords
{
    protected static string $resource = AnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('topbar_mobile_settings')
                ->label('📱 Mobile Topbar & Visibility Settings')
                ->icon('heroicon-o-device-phone-mobile')
                ->color('success')
                ->url(url('/admin/homepage-settings')),
            Actions\CreateAction::make(),
        ];
    }
}
