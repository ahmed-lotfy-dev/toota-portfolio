<div class="p-6">
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Projects</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage your portfolio projects</p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="flex gap-2 w-full sm:w-auto">
                <x-ui.button wire:click="showProjectModal">
                    Add Project
                </x-ui.button>
            </div>

        </div>
    </div>

    @if (session()->has('message'))
        <div
            class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md dark:bg-green-900/20 dark:border-green-800 dark:text-green-400">
            {{ session('message') }}
        </div>
    @endif

    <!-- Projects Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Title</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Category</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Status</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Images</th>
                    <th
                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($projects as $project)
                    <tr class="align-top"> {{-- Added align-top to prevent image section from pushing other content too high
                        --}}
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                            <div class="truncate max-w-xs" title="{{ $project->title }}">
                                {{ $project->title }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            <div class="truncate max-w-xs" title="{{ $project->category->name }}">
                                {{ $project->category->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex gap-2">
                                <x-ui.button size="sm" variant="secondary" wire:click="toggleFeatured({{ $project->id }})">
                                    {{ $project->is_featured ? '★ Featured' : '☆ Feature' }}
                                </x-ui.button>
                                <x-ui.badge color="{{ $project->is_published ? 'green' : 'red' }}"
                                    wire:click="togglePublished({{ $project->id }})" class="cursor-pointer">
                                    {{ $project->is_published ? 'Published' : 'Draft' }}
                                </x-ui.badge>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex -space-x-2 overflow-hidden">
                                @forelse($project->images->take(3) as $image)
                                    <img class="inline-block h-10 w-10 rounded-full ring-2 ring-white dark:ring-gray-800 object-cover"
                                        src="{{ $image->url }}" alt="Project Image">
                                @empty
                                    <span class="text-gray-400 dark:text-gray-600">No Images</span>
                                @endforelse
                                @if($project->images->count() > 3)
                                    <span
                                        class="inline-flex items-center justify-center h-10 w-10 rounded-full ring-2 ring-white dark:ring-gray-800 bg-gray-100 dark:bg-gray-700 text-xs font-medium text-gray-500 dark:text-gray-300">
                                        +{{ $project->images->count() - 3 }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <x-ui.button variant="link" wire:click="edit({{ $project->id }})" class="mr-3">
                                Edit
                            </x-ui.button>
                            <x-ui.button variant="link"
                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                wire:click="delete({{ $project->id }})" onclick="return confirm('Are you sure?')">
                                Delete
                            </x-ui.button>
                        </td>
                    </tr>
                    @if($showProjectImages[$project->id] ?? false)
                        <tr>
                            <td colspan="5" class="p-4 bg-gray-50 dark:bg-gray-900">
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                    @foreach($project->images->sortBy('order') as $image)
                                        <div class="relative group">
                                            <img src="{{ $image->url }}" alt="{{ $image->caption ?? 'Project Image' }}"
                                                class="w-full h-32 object-cover rounded shadow">
                                            @if($image->is_primary)
                                                <span
                                                    class="absolute top-1 left-1 px-2 py-0.5 bg-blue-500 text-white text-xs rounded-full">Primary</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No projects found. Create your first project above.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>



    <x-ui.modal name="project-modal" title="Project Details">
        <x-slot name="body">
            <livewire:dashboard.forms.project-form-modal-content />
        </x-slot>
    </x-ui.modal>


</div>