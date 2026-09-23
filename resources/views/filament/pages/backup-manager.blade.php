<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Actions --}}
        <div class="flex flex-wrap gap-3">
            <button wire:click="createBackup" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition disabled:opacity-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                <span wire:loading.remove wire:target="createBackup">DB Backup</span>
                <span wire:loading wire:target="createBackup">Creating...</span>
            </button>
            <button wire:click="createFullBackup" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition disabled:opacity-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                <span wire:loading.remove wire:target="createFullBackup">Full Backup</span>
                <span wire:loading wire:target="createFullBackup">Creating...</span>
            </button>
            <button wire:click="cleanBackups" wire:confirm="Remove old backups according to retention policy?"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
                Clean Old Backups
            </button>
        </div>

        {{-- Backup List --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50 text-left">
                        <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">File</th>
                        <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Size</th>
                        <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">Date</th>
                        <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->getBackups() as $backup)
                    <tr class="border-t border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750">
                        <td class="px-4 py-3 font-mono text-xs">{{ $backup['name'] }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $backup['size'] }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $backup['date'] }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <button wire:click="downloadBackup('{{ $backup['name'] }}')" class="text-primary-600 hover:underline text-xs">Download</button>
                            <button wire:click="deleteBackup('{{ $backup['name'] }}')" wire:confirm="Delete this backup?" class="text-red-600 hover:underline text-xs">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-400">No backups found. Create your first backup above.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
