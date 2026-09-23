<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use App\Models\Role;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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
