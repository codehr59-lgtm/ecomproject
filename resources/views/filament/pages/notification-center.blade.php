<x-filament-panels::page>
    <div class="space-y-4">
        {{-- Filter + Actions --}}
        <div class="flex items-center justify-between">
            <div class="flex gap-2">
                <button wire:click="$set('filter', 'all')"
                        class="px-3 py-1.5 text-sm rounded-lg transition {{ $filter === 'all' ? 'bg-primary-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}">
                    All
                </button>
                <button wire:click="$set('filter', 'unread')"
                        class="px-3 py-1.5 text-sm rounded-lg transition {{ $filter === 'unread' ? 'bg-primary-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}">
                    Unread
                </button>
            </div>
            <button wire:click="markAllAsRead" class="text-sm text-primary-600 hover:underline">
                Mark all as read
            </button>
        </div>

        {{-- Notifications --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($this->getNotifications() as $notification)
            <div class="flex items-start gap-4 px-4 py-4 {{ !$notification->read_at ? 'bg-primary-50/50 dark:bg-primary-900/10' : '' }} hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                {{-- Icon --}}
                <div class="flex-shrink-0 mt-0.5">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full
                        @switch($notification->type)
                            @case('success') bg-green-100 text-green-600 @break
                            @case('warning') bg-yellow-100 text-yellow-600 @break
                            @case('danger') bg-red-100 text-red-600 @break
                            @default bg-blue-100 text-blue-600
                        @endswitch
                    ">
                        @switch($notification->type)
                            @case('success') <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> @break
                            @case('warning') <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg> @break
                            @case('danger') <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg> @break
                            @default <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        @endswitch
                    </span>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ $notification->title }}
                        @if(!$notification->read_at)
                            <span class="inline-block w-2 h-2 rounded-full bg-primary-500 ml-1"></span>
                        @endif
                    </p>
                    @if($notification->body)
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $notification->body }}</p>
                    @endif
                    <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    @if($notification->action_url)
                        <a href="{{ $notification->action_url }}" class="text-xs text-primary-600 hover:underline">View</a>
                    @endif
                    @if(!$notification->read_at)
                        <button wire:click="markAsRead({{ $notification->id }})" class="text-xs text-gray-400 hover:text-gray-600">Mark read</button>
                    @endif
                    <button wire:click="deleteNotification({{ $notification->id }})" class="text-xs text-red-400 hover:text-red-600">Remove</button>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-400">
                No notifications.
            </div>
            @endforelse
        </div>
    </div>
</x-filament-panels::page>
