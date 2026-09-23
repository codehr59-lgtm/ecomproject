<?php

namespace App\Filament\Pages;

use App\Models\AdminNotification;
use Filament\Pages\Page;

class NotificationCenter extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?string $navigationLabel = 'Notifications';

    protected static ?int $navigationSort = 34;

    protected static string $view = 'filament.pages.notification-center';

    public string $filter = 'all';

    public function getNotifications(): \Illuminate\Database\Eloquent\Collection
    {
        $query = AdminNotification::forUser(auth()->id())
            ->latest()
            ->limit(50);

        if ($this->filter === 'unread') {
            $query->unread();
        }

        return $query->get();
    }

    public function markAsRead(int $id): void
    {
        AdminNotification::find($id)?->markAsRead();
    }

    public function markAllAsRead(): void
    {
        AdminNotification::forUser(auth()->id())
            ->unread()
            ->update(['read_at' => now()]);
    }

    public function deleteNotification(int $id): void
    {
        AdminNotification::find($id)?->delete();
    }

    public static function getNavigationBadge(): ?string
    {
        $count = AdminNotification::forUser(auth()->id())->unread()->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }
}
