<div class="p-6">
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Projects</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage your portfolio projects</p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="button" wire:click="toggleAddProject"
                    class="flex-1 sm:flex-none h-10 px-5 bg-blue-500 text-white font-semibold rounded hover:bg-blue-700">
                    {{ $showAddProjectModal ? 'Cancel' : 'Add Project' }}
                </button>
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
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
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
                    <tr class="align-top"> {{-- Added align-top to prevent image section from pushing other content too high --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $project->title }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $project->category->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex gap-2">
                                <button wire:click="toggleFeatured({{ $project->id }})"
                                    class="px-2 py-1 text-xs rounded {{ $project->is_featured ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                    {{ $project->is_featured ? '★ Featured' : '☆ Feature' }}
                                </button>
                                <button wire:click="togglePublished({{ $project->id }})"
                                    class="px-2 py-1 text-xs rounded {{ $project->is_published ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                    {{ $project->is_published ? 'Published' : 'Draft' }}
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            @if($project->images->isNotEmpty())
                                <button wire:click="toggleProjectImages({{ $project->id }})" class="flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        @if($showProjectImages[$project->id] ?? false)
                                            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                        @else
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        @endif
                                    </svg>
                                    View ({{ $project->images->count() }})
                                </button>
                            @else
                                <span class="text-gray-400 dark:text-gray-600">No Images</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="edit({{ $project->id }})"
                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                Edit
                            </button>
                            <button wire:click="delete({{ $project->id }})" onclick="return confirm('Are you sure?')"
                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @if($showProjectImages[$project->id] ?? false)
                        <tr>
                            <td colspan="5" class="p-4 bg-gray-50 dark:bg-gray-900">
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                    @foreach($project->images->sortBy('order') as $image)
                                        <div class="relative group">
                                            <img src="{{ Storage::disk('r2')->url($image->image_path) }}" alt="{{ $image->caption ?? 'Project Image' }}" class="w-full h-32 object-cover rounded shadow">
                                            @if($image->is_primary)
                                                <span class="absolute top-1 left-1 px-2 py-0.5 bg-blue-500 text-white text-xs rounded-full">Primary</span>
                                            @endif
                                            <button type="button" wire:click="removeImage({{ $image->id }})" onclick="return confirm('Are you sure you want to delete this image? This action cannot be undone.');"
                                                class="absolute top-2 right-2 bg-red-500 text-white w-8 h-8 flex items-center justify-center rounded-full text-base shadow-md">
                                                &times;
                                            </button>
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

    @if($showAddProjectModal)
    <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-30">
        <div class="bg-white dark:bg-gray-800 rounded shadow-lg p-6 w-full max-w-md relative">
            <button wire:click="hideAddProject" class="absolute top-3 right-3 text-2xl">&times;</button>
            <h3 class="text-lg font-bold mb-4">{{ $editingId ? 'Edit Project' : 'Add New Project' }}</h3>
            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="block mb-1">Title *</label>
                    <input type="text" wire:model="title"
                        class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white @error('title') border-red-500 @enderror">
                    @error('title')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block mb-1">Category *</label>
                    <select wire:model="category_id"
                        class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white @error('category_id') border-red-500 @enderror">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block mb-1">Description</label>
                    <textarea wire:model="description"
                        class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white"></textarea>
                </div>
                <div class="flex gap-4">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="is_featured" class="mr-2">
                        <span class="text-sm">Featured</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="is_published" class="mr-2">
                        <span class="text-sm">Published</span>
                    </label>
                </div>
                <div>
                    <label class="block mb-1">Upload New Images</label>
                    <input type="file" wire:model="newImages" multiple
                        class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
                    @error('newImages.*')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror

                    {{-- Live preview for new images --}}
                    @if ($newImages)
                        <div class="mt-2 grid grid-cols-3 gap-4">
                            @foreach ($newImages as $newImage)
                                <img src="{{ $newImage->temporaryUrl() }}" class="w-full h-32 object-cover rounded">
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Display existing images --}}
                @if ($images)
                    <div class="mt-4 grid grid-cols-3 gap-4">
                        @foreach ($images as $image)
                            <div class="relative group">
                                <img src="{{ Storage::disk('r2')->url($image['path']) }}" alt="Project Image" class="w-full h-32 object-cover rounded">
                                <button type="button" wire:click="removeImage({{ $image['id'] }})" onclick="return confirm('Are you sure you want to delete this image? This action cannot be undone.');"
                                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-base shadow-md">
                                    &times;
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">{{ $editingId ? 'Save Changes' : 'Add' }}</button>
                    <button type="button" wire:click="hideAddProject"
                        class="px-4 py-2 bg-gray-400 rounded hover:bg-gray-500">Cancel</button>
                </div>
            </form>
                </div>
            </div>
        @endif
        
        </div>
@if($showAddCategoryModal)
    <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-30">
        <div class="bg-white dark:bg-gray-800 rounded shadow-lg p-6 w-full max-w-md relative">
            <button wire:click="hideAddCategory" class="absolute top-3 right-3 text-2xl">&times;</button>
            <h3 class="text-lg font-bold mb-4">Add New Category</h3>
            <form wire:submit.prevent="saveNewCategory" class="space-y-4">
                <div>
                    <label class="block mb-1">Name *</label>
                    <input type="text" wire:model.defer="newCategoryName"
                        class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
                    @error('newCategoryName')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block mb-1">Description</label>
                    <textarea wire:model.defer="newCategoryDescription"
                        class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white"></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add</button>
                    <button type="button" wire:click="hideAddCategory"
                        class="px-4 py-2 bg-gray-400 rounded hover:bg-gray-500">Cancel</button>
                </div>
            </form>
        </div>
    </div>
@endif