<div class="p-6">
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <a href="{{ route('projects.index') }}" class="text-blue-500 hover:underline">&larr; Back to Projects</a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $project->title }}</h1>
            <p class="text-gray-600 dark:text-gray-400">Category: {{ $project->category->name }}</p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <x-ui.button wire:click="edit">
                Edit Project
            </x-ui.button>
            <x-ui.button variant="danger" wire:click="delete" onclick="return confirm('Are you sure?')">
                Delete Project
            </x-ui.button>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Project Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2">
                <h3 class="font-bold text-gray-900 dark:text-white mb-2">Description</h3>
                <div class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap">
                    {{ $project->description }}
                </div>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-2">Status</h3>
                <div class="flex gap-2">
                    <x-ui.badge color="{{ $project->is_published ? 'green' : 'red' }}">
                        {{ $project->is_published ? 'Published' : 'Draft' }}
                    </x-ui.badge>
                    @if($project->is_featured)
                        <x-ui.badge color="blue">
                            ★ Featured
                        </x-ui.badge>
                    @endif
                </div>

                <h3 class="font-bold text-gray-900 dark:text-white mt-6 mb-2">Dates</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Created: {{ $project->created_at->format('M d, Y') }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Last Updated: {{ $project->updated_at->format('M d, Y') }}</p>
            </div>
        </div>

        <h3 class="font-bold text-gray-900 dark:text-white mt-8 mb-4">Images</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @forelse($project->images as $image)
                <div class="relative group">
                    <img src="{{ $image->url }}" alt="{{ $image->caption ?? 'Project Image' }}" class="w-full h-32 object-cover rounded shadow">
                    @if($image->is_primary)
                        <span class="absolute top-1 left-1 px-2 py-0.5 bg-blue-500 text-white text-xs rounded-full">Primary</span>
                    @endif
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400">No images for this project.</p>
            @endforelse
        </div>
    </div>
</div>
