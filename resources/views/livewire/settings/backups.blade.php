<section class="w-full p-8 space-y-8">

    <div>
        <flux:heading>{{ __('System Backups & Exports') }}</flux:heading>
        <flux:subheading>{{ __('Manage your data portability and disaster recovery.') }}</flux:subheading>
    </div>

    <flux:separator />

    {{-- Instant Downloads Panel --}}
    <div class="bg-white dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 space-y-6">
        <div>
            <flux:heading size="lg">{{ __('Instant Downloads') }}</flux:heading>
            <flux:subheading>{{ __('Select a backup type to generate and download immediately.') }}</flux:subheading>
        </div>

        <div class="space-y-4">
            {{-- Selection Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- JSON Option --}}
                <div wire:click="$set('selectedDownloadType', 'json')"
                    class="cursor-pointer relative flex items-start p-4 rounded-xl border transition-all duration-200 
                     {{ $selectedDownloadType === 'json' ? 'border-indigo-600 bg-indigo-50/50 dark:bg-indigo-900/10 ring-1 ring-indigo-600' : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ __('JSON Data') }}</span>
                            @if($selectedDownloadType === 'json')
                                <flux:icon.check-circle class="w-5 h-5 text-indigo-600" variant="solid" />
                            @endif
                        </div>
                        <p class="text-xs text-zinc-500">
                            {{ __('Lightweight export of Projects, Categories, and Testimonials.') }}
                        </p>
                    </div>
                </div>

                {{-- Media Option --}}
                <div wire:click="$set('selectedDownloadType', 'media')"
                    class="cursor-pointer relative flex items-start p-4 rounded-xl border transition-all duration-200
                     {{ $selectedDownloadType === 'media' ? 'border-indigo-600 bg-indigo-50/50 dark:bg-indigo-900/10 ring-1 ring-indigo-600' : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ __('Media Archive') }}</span>
                            @if($selectedDownloadType === 'media')
                                <flux:icon.check-circle class="w-5 h-5 text-indigo-600" variant="solid" />
                            @endif
                        </div>
                        <p class="text-xs text-zinc-500">
                            {{ __('ZIP archive containing all project images organized by folder.') }}
                        </p>
                    </div>
                </div>

                {{-- SQL Option --}}
                <div wire:click="$set('selectedDownloadType', 'sql')"
                    class="cursor-pointer relative flex items-start p-4 rounded-xl border transition-all duration-200
                     {{ $selectedDownloadType === 'sql' ? 'border-indigo-600 bg-indigo-50/50 dark:bg-indigo-900/10 ring-1 ring-indigo-600' : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ __('Database SQL') }}</span>
                            @if($selectedDownloadType === 'sql')
                                <flux:icon.check-circle class="w-5 h-5 text-indigo-600" variant="solid" />
                            @endif
                        </div>
                        <p class="text-xs text-zinc-500">{{ __('Raw PostgreSQL dump file for manual restoration.') }}
                        </p>
                    </div>
                </div>

                {{-- Full Option --}}
                <div wire:click="$set('selectedDownloadType', 'full')"
                    class="cursor-pointer relative flex items-start p-4 rounded-xl border transition-all duration-200
                     {{ $selectedDownloadType === 'full' ? 'border-indigo-600 bg-indigo-50/50 dark:bg-indigo-900/10 ring-1 ring-indigo-600' : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ __('Full Backup') }}</span>
                            @if($selectedDownloadType === 'full')
                                <flux:icon.check-circle class="w-5 h-5 text-indigo-600" variant="solid" />
                            @endif
                        </div>
                        <p class="text-xs text-zinc-500">
                            {{ __('Complete package including Database SQL and Media ZIP.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Action Button --}}
            <div class="pt-2">
                <flux:button wire:click="triggerDownload" wire:loading.attr="disabled" variant="primary"
                    icon="arrow-down-tray" class="w-full md:w-auto min-w-[200px]">
                    <span wire:loading.remove wire:target="triggerDownload">{{ __('Download Selected') }}</span>
                    <span wire:loading wire:target="triggerDownload">{{ __('Processing...') }}</span>
                </flux:button>
            </div>
        </div>
    </div>

    {{-- Cloud Backups Panel --}}
    <div class="bg-white dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 space-y-6">
        <div>
            <flux:heading size="lg">{{ __('Cloud Backup (R2)') }}</flux:heading>
            <flux:subheading>{{ __('Securely store your backups on Cloudflare R2.') }}</flux:subheading>
        </div>

        <div class="space-y-4">
            {{-- Selection Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- DB Cloud Option --}}
                <div wire:click="$set('selectedCloudType', 'db')"
                    class="cursor-pointer relative flex items-start p-4 rounded-xl border transition-all duration-200
                     {{ $selectedCloudType === 'db' ? 'border-sky-500 bg-sky-50/50 dark:bg-sky-900/10 ring-1 ring-sky-500' : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ __('Database Only') }}</span>
                            @if($selectedCloudType === 'db')
                                <flux:icon.check-circle class="w-5 h-5 text-sky-500" variant="solid" />
                            @endif
                        </div>
                        <p class="text-xs text-zinc-500">{{ __('Quickly backup your database to the cloud.') }}</p>
                    </div>
                </div>

                {{-- Full Cloud Option --}}
                <div wire:click="$set('selectedCloudType', 'full')"
                    class="cursor-pointer relative flex items-start p-4 rounded-xl border transition-all duration-200
                     {{ $selectedCloudType === 'full' ? 'border-sky-500 bg-sky-50/50 dark:bg-sky-900/10 ring-1 ring-sky-500' : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <span
                                class="font-medium text-zinc-900 dark:text-zinc-100">{{ __('Full Project Backup') }}</span>
                            @if($selectedCloudType === 'full')
                                <flux:icon.check-circle class="w-5 h-5 text-sky-500" variant="solid" />
                            @endif
                        </div>
                        <p class="text-xs text-zinc-500">{{ __('Upload Database + All Media Files to R2.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Action Button --}}
            <div class="pt-2">
                <flux:button wire:click="triggerCloudBackup" wire:loading.attr="disabled" icon="cloud-arrow-up"
                    class="w-full md:w-auto min-w-[200px]">
                    <span wire:loading.remove
                        wire:target="triggerCloudBackup">{{ __('Upload Selected to Cloud') }}</span>
                    <span wire:loading wire:target="triggerCloudBackup">{{ __('Uploading...') }}</span>
                </flux:button>
            </div>
        </div>
    </div>

    {{-- Scheduled Backups Panel --}}
    <div class="bg-white dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 space-y-6">
        <div class="flex items-start justify-between">
            <div>
                <flux:heading size="lg">{{ __('Scheduled Backups') }}</flux:heading>
                <flux:subheading>{{ __('Automate your backup strategy.') }}</flux:subheading>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Enable Schedule') }}</span>
                <flux:switch wire:model.live="schedule.enabled" />
            </div>
        </div>

        @if($schedule['enabled'] ?? false)
            <div class="p-4 bg-zinc-50 dark:bg-zinc-900/50 rounded-lg border border-zinc-100 dark:border-zinc-700/50">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Frequency --}}
                    <div class="space-y-3">
                        <flux:label>{{ __('Frequency') }}</flux:label>
                        <flux:radio.group wire:model.live="schedule.frequency">
                            <flux:radio value="daily" label="{{ __('Daily') }}" />
                            <flux:radio value="weekly" label="{{ __('Weekly') }}" />
                            <flux:radio value="monthly" label="{{ __('Monthly') }}" />
                        </flux:radio.group>
                    </div>

                    {{-- Time --}}
                    <div class="space-y-3">
                        <flux:label>{{ __('Run Time (24h)') }}</flux:label>
                        <flux:input wire:model="schedule.time" type="time" class="w-full" />
                    </div>

                    {{-- Retention --}}
                    <div class="space-y-3">
                        <flux:label>{{ __('Retention Limit') }}</flux:label>
                        @if(($schedule['frequency'] ?? 'daily') === 'daily')
                            <flux:input wire:model="schedule.keep_daily" type="number" min="1" max="365" label="Days to keep"
                                description="Older backups will be deleted." />
                        @elseif(($schedule['frequency'] ?? 'daily') === 'weekly')
                            <flux:input wire:model="schedule.keep_weekly" type="number" min="1" max="52" label="Weeks to keep"
                                description="Older backups will be deleted." />
                        @else
                            <flux:input wire:model="schedule.keep_monthly" type="number" min="1" max="24" label="Months to keep"
                                description="Older backups will be deleted." />
                        @endif
                    </div>
                </div>

                <div class="flex justify-end mt-6 pt-4 border-t border-zinc-200 dark:border-zinc-700/50">
                    <flux:button wire:click="saveSchedule" variant="filled">
                        {{ __('Save Schedule Settings') }}
                    </flux:button>
                </div>
            </div>
        @endif
    </div>

    <flux:separator />

    {{-- Cloud Backup History --}}
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <flux:heading size="lg">{{ __('Cloud Backup History') }}</flux:heading>
                <flux:subheading>{{ __('Browse and retrieve your backups stored in Cloudflare R2.') }}</flux:subheading>
            </div>
            <flux:button wire:click="refreshBackups" icon="arrow-path" size="sm" variant="ghost">
                {{ __('Refresh List') }}
            </flux:button>
        </div>

        {{-- Custom Card + Table Style --}}
        <div
            class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden bg-white dark:bg-zinc-800/50 shadow-sm">
            @if(count($backups) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-900/50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    {{ __('Date & Time') }}
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    {{ __('Storage Disk') }}
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    {{ __('File Size') }}
                                </th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">{{ __('Actions') }}</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700 bg-white dark:bg-zinc-800/50">
                            @foreach($backups as $backup)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-zinc-200">
                                        {{ $backup['date'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                        <div
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">
                                            {{ $backup['disk'] }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400 font-mono">
                                        {{ $backup['size_formatted'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <flux:dropdown>
                                            <flux:button size="sm" icon="ellipsis-horizontal" variant="ghost" />

                                            <flux:menu>
                                                <flux:menu.item
                                                    wire:click="downloadBackup('{{ $backup['disk'] }}', '{{ $backup['path'] }}')"
                                                    icon="arrow-down-tray">
                                                    {{ __('Download') }}
                                                </flux:menu.item>

                                                <flux:menu.separator />

                                                <flux:menu.item
                                                    wire:click="deleteBackup('{{ $backup['disk'] }}', '{{ $backup['path'] }}')"
                                                    icon="trash" variant="danger"
                                                    wire:confirm="{{ __('Are you sure you want to delete this backup?') }}">
                                                    {{ __('Delete') }}
                                                </flux:menu.item>
                                            </flux:menu>
                                        </flux:dropdown>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex flex-col items-center justified-center py-16 text-center">
                    <div class="w-16 h-16 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                        <flux:icon.circle-stack class="w-8 h-8 text-zinc-400" />
                    </div>
                    <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">{{ __('No backups found') }}</h3>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400 max-w-sm">
                        {{ __('Start a manual cloud backup or wait for the schedule to run.') }}
                    </p>
                    {{-- Debug Helper: Removing this in prod, but helpful if user says none found --}}
                </div>
            @endif
        </div>
    </div>
</section>