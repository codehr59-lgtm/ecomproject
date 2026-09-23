<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['gallery'] = $this->record->images()
            ->orderBy('sort')
            ->pluck('path')
            ->toArray();

        return $data;
    }

    protected function afterSave(): void
    {
        $gallery = $this->data['gallery'] ?? [];

        $this->record->images()->delete();

        if (!empty($gallery)) {
            $rows = [];
            foreach ($gallery as $i => $path) {
                $rows[] = ['path' => $path, 'sort' => $i];
            }
            $this->record->images()->createMany($rows);
        }

        $this->record->syncStock();
    }
}
