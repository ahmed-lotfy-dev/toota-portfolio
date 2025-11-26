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
                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($projects as $project)
                    <tr>
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
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
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
            <h3 class="text-lg font-bold mb-4">Add New Project</h3>
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
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add</button>
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