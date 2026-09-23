<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?string $navigationLabel = 'Roles & Permissions';

    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        $allPermissions = Role::allPermissions();

        return $form
            ->schema([
                Forms\Components\Section::make('Role Info')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->placeholder('e.g. editor'),

                        Forms\Components\TextInput::make('display_name')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('e.g. Content Editor'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Permissions')
                    ->schema(
                        collect($allPermissions)->map(function (array $perms, string $group) {
                            return Forms\Components\CheckboxList::make("perm_{$group}")
                                ->label($group)
                                ->options($perms)
                                ->columns(2)
                                ->bulkToggleable();
                        })->values()->toArray()
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('display_name')
                    ->label('Role')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Users'),

                Tables\Columns\TextColumn::make('permissions')
                    ->label('Permissions')
                    ->formatStateUsing(function ($state) {
                        $perms = is_array($state) ? $state : json_decode($state, true);
                        if (! $perms) return '0';
                        if (in_array('*', $perms, true)) return 'All';
                        return count($perms);
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit'   => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
