<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    public function mount(): void
    {
        parent::mount();

        $parentId = request()->query('parent_id');

        if ($parentId && Category::find($parentId)) {
            $this->form->fill([
                'parent_id' => (int) $parentId,
                'is_active' => true,
                'sort'      => 0,
            ]);
        }
    }
}
