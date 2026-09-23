<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (($data['product_type'] ?? 'simple') === 'variable') {
            $data['price'] = $data['price'] ?? 0;
            $data['stock'] = $data['stock'] ?? 0;
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $gallery = $this->data['gallery'] ?? [];

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
