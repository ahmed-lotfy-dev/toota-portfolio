<section class="w-full p-8 space-y-8">

    <div>
        <flux:heading>{{ __('System Backups & Exports') }}</flux:heading>
        <flux:subheading>{{ __('Manage your data portability and disaster recovery.') }}</flux:subheading>
    </div>

    <flux:separator />

    {{-- Data Portability Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Custom Card Style --}}
        <div class="p-6 bg-white dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-xl space-y-4">
            <div>
                <flux:heading size="lg">{{ __('Export Data') }}</flux:heading>
                <flux:subheading>{{ __('Download a JSON file containing all projects, categories, and testimonials.') }}
                </flux:subheading>
            </div>

            <flux:button wire:click="downloadJson" icon="arrow-down-tray" variant="primary" class="w-full">
                {{ __('Download JSON') }}
            </flux:button>
        </div>

        <div class="p-6 bg-white dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-xl space-y-4">
            <div>
                <flux:heading size="lg">{{ __('Media Archive') }}</flux:heading>
                <flux:subheading>{{ __('Download a ZIP file with all project photos organized by folders.') }}
                </flux:subheading>
            </div>

            <flux:button wire:click="downloadMediaArchive" wire:loading.attr="disabled" icon="photo" class="w-full">
                <span wire:loading.remove wire:target="downloadMediaArchive">{{ __('Download Media ZIP') }}</span>
                <span wire:loading wire:target="downloadMediaArchive">{{ __('Archiving...') }}</span>
            </flux:button>
        </div>
    </div>

    <flux:separator />

    {{-- Database Backups Section --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <flux:heading size="lg">{{ __('Database Backups') }}</flux:heading>
                <flux:subheading>{{ __('Manage automatic and manual SQL backups stored in Cloud (R2).') }}
                </flux:subheading>
            </div>
            <flux:button wire:click="createBackup" wire:loading.attr="disabled" icon="circle-stack" variant="filled">
                <span wire:loading.remove wire:target="createBackup">{{ __('Backup Now') }}</span>
                <span wire:loading wire:target="createBackup">{{ __('Backing up...') }}</span>
            </flux:button>
        </div>

        {{-- Custom Card + Table Style --}}
        <div
            class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden bg-white dark:bg-zinc-800/50">
            @if(count($backups) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    {{ __('Date') }}
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    {{ __('Disk') }}
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    {{ __('Size') }}
                                </th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">{{ __('Actions') }}</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach($backups as $backup)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-200">
                                        {{ $backup['date'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                        <flux:badge size="sm">{{ $backup['disk'] }}</flux:badge>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                        {{ \Illuminate\Support\Number::fileSize($backup['size']) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <flux:button
                                            wire:click="downloadBackup('{{ $backup['disk'] }}', '{{ $backup['path'] }}')"
                                            size="sm" icon="arrow-down-tray" variant="ghost">
                                            {{ __('Download') }}
                                        </flux:button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <flux:icon.circle-stack class="w-12 h-12 mx-auto text-zinc-300 dark:text-zinc-600 mb-3" />
                    <p class="text-zinc-500 dark:text-zinc-400">{{ __('No backups found yet.') }}</p>
                </div>
            @endif
        </div>
    </div>
</section>