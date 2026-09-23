<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class BackupManager extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?string $navigationLabel = 'Backups';

    protected static ?int $navigationSort = 32;

    protected static string $view = 'filament.pages.backup-manager';

    public function getBackups(): array
    {
        $backupPath = config('backup.backup.destination.disks', ['local']);
        $disk = $backupPath[0] ?? 'local';
        $basePath = config('backup.backup.name', 'ecom-shuvo');

        $storagePath = storage_path("app/{$basePath}");

        if (! File::isDirectory($storagePath)) {
            return [];
        }

        return collect(File::files($storagePath))
            ->filter(fn ($file) => $file->getExtension() === 'zip')
            ->sortByDesc(fn ($file) => $file->getMTime())
            ->map(fn ($file) => [
                'name'     => $file->getFilename(),
                'size'     => $this->formatSize($file->getSize()),
                'date'     => date('d M Y, h:i A', $file->getMTime()),
                'path'     => $file->getPathname(),
            ])
            ->values()
            ->toArray();
    }

    public function createBackup(): void
    {
        try {
            Artisan::call('backup:run', ['--only-db' => true]);

            Notification::make()
                ->title('Database backup created successfully')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Backup failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function createFullBackup(): void
    {
        try {
            Artisan::call('backup:run');

            Notification::make()
                ->title('Full backup created successfully')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Backup failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function cleanBackups(): void
    {
        try {
            Artisan::call('backup:clean');

            Notification::make()
                ->title('Old backups cleaned')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Cleanup failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function downloadBackup(string $filename): ?\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $basePath = config('backup.backup.name', 'ecom-shuvo');
        $path = storage_path("app/{$basePath}/" . basename($filename));

        if (File::exists($path)) {
            return response()->download($path);
        }

        Notification::make()
            ->title('Backup file not found')
            ->danger()
            ->send();

        return null;
    }

    public function deleteBackup(string $filename): void
    {
        $basePath = config('backup.backup.name', 'ecom-shuvo');
        $path = storage_path("app/{$basePath}/" . basename($filename));

        if (File::exists($path)) {
            File::delete($path);
            Notification::make()
                ->title('Backup deleted')
                ->success()
                ->send();
        }
    }

    private function formatSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 1) . ' ' . $units[$i];
    }
}
