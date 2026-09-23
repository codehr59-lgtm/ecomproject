<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\File;

class LogsViewer extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?string $navigationLabel = 'Logs Viewer';

    protected static ?int $navigationSort = 31;

    protected static string $view = 'filament.pages.logs-viewer';

    public string $selectedFile = '';
    public string $search = '';
    public string $level = '';
    public int $lines = 100;

    public function mount(): void
    {
        $files = $this->getLogFiles();
        $this->selectedFile = $files[0] ?? '';
    }

    public function getLogFiles(): array
    {
        $logPath = storage_path('logs');

        if (! File::isDirectory($logPath)) {
            return [];
        }

        return collect(File::files($logPath))
            ->filter(fn ($file) => $file->getExtension() === 'log')
            ->sortByDesc(fn ($file) => $file->getMTime())
            ->map(fn ($file) => $file->getFilename())
            ->values()
            ->toArray();
    }

    public function getLogContent(): array
    {
        if (! $this->selectedFile) {
            return [];
        }

        $path = storage_path('logs/' . basename($this->selectedFile));

        if (! File::exists($path)) {
            return [];
        }

        $content = File::get($path);
        $entries = preg_split('/(?=\[\d{4}-\d{2}-\d{2})/', $content, -1, PREG_SPLIT_NO_EMPTY);

        $entries = array_reverse(array_slice($entries, -$this->lines));

        if ($this->level) {
            $entries = array_filter($entries, fn ($entry) => str_contains(strtolower($entry), '.' . strtolower($this->level)));
        }

        if ($this->search) {
            $entries = array_filter($entries, fn ($entry) => stripos($entry, $this->search) !== false);
        }

        return array_map(function ($entry) {
            $level = 'info';
            if (preg_match('/\.(\w+):/', $entry, $m)) {
                $level = strtolower($m[1]);
            }
            return ['level' => $level, 'content' => trim($entry)];
        }, array_values($entries));
    }

    public function clearLog(): void
    {
        if ($this->selectedFile) {
            $path = storage_path('logs/' . basename($this->selectedFile));
            if (File::exists($path)) {
                File::put($path, '');
            }
        }
    }
}
