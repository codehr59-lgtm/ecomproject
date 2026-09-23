<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use App\Models\Role;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $permissions = $data['permissions'] ?? [];

        foreach (Role::allPermissions() as $group => $perms) {
            $data["perm_{$group}"] = array_intersect(array_keys($perms), $permissions);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $permissions = [];
        foreach (Role::allPermissions() as $group => $perms) {
            $key = "perm_{$group}";
            if (! empty($data[$key])) {
                $permissions = array_merge($permissions, $data[$key]);
            }
            unset($data[$key]);
        }
        $data['permissions'] = $permissions;

        return $data;
    }
}
