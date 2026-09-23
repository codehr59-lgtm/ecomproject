<x-filament-panels::page>
    <div class="space-y-4">
        {{-- Filters --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Log File</label>
                <select wire:model.live="selectedFile" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-sm">
                    @foreach($this->getLogFiles() as $file)
                        <option value="{{ $file }}">{{ $file }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Level</label>
                <select wire:model.live="level" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-sm">
                    <option value="">All Levels</option>
                    <option value="emergency">Emergency</option>
                    <option value="alert">Alert</option>
                    <option value="critical">Critical</option>
                    <option value="error">Error</option>
                    <option value="warning">Warning</option>
                    <option value="notice">Notice</option>
                    <option value="info">Info</option>
                    <option value="debug">Debug</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search logs..." class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-sm">
            </div>
            <div class="flex items-end">
                <button wire:click="clearLog" wire:confirm="Are you sure you want to clear this log file?" class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                    Clear Log
                </button>
            </div>
        </div>

        {{-- Log Entries --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="max-h-[600px] overflow-y-auto">
                @forelse($this->getLogContent() as $entry)
                    <div class="border-b border-gray-100 dark:border-gray-700 px-4 py-3 text-sm font-mono hover:bg-gray-50 dark:hover:bg-gray-750">
                        <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold mr-2
                            @switch($entry['level'])
                                @case('emergency') @case('alert') @case('critical')
                                    bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 @break
                                @case('error')
                                    bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400 @break
                                @case('warning')
                                    bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 @break
                                @case('info') @case('notice')
                                    bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 @break
                                @default
                                    bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                            @endswitch
                        ">{{ strtoupper($entry['level']) }}</span>
                        <pre class="whitespace-pre-wrap text-gray-600 dark:text-gray-400 mt-1 text-xs leading-relaxed">{{ \Illuminate\Support\Str::limit($entry['content'], 1000) }}</pre>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400">
                        No log entries found.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-panels::page>
