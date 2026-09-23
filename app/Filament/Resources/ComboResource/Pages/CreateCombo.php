<?php

namespace App\Filament\Resources\ComboResource\Pages;

use App\Filament\Resources\ComboResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCombo extends CreateRecord
{
    protected static string $resource = ComboResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
