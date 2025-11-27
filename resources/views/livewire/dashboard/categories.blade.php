<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Categories</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage your project categories</p>
        </div>
        <button type="button" wire:click="showAddCategory" class="h-10 px-5 bg-blue-500 text-white font-bold rounded hover:bg-blue-700">Add Category</button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md dark:bg-green-900/20 dark:border-green-800 dark:text-green-400">
            {{ session('message') }}
        </div>
    @endif

    @if($showAddCategoryModal)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-30">
            <div class="bg-white dark:bg-gray-800 rounded shadow-lg p-6 w-full max-w-md relative">
                <button wire:click="hideAddCategory" class="absolute top-3 right-3 text-2xl">&times;</button>
                <h3 class="text-lg font-bold mb-4">Add New Category</h3>
                <form wire:submit.prevent="saveNewCategory" class="space-y-4">
                    <div>
                        <label class="block mb-1">Name *</label>
                        <input type="text" wire:model.defer="newCategoryName" class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
                        @error('newCategoryName')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block mb-1">Description</label>
                        <textarea wire:model.defer="newCategoryDescription" class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white"></textarea>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add</button>
                        <button type="button" wire:click="hideAddCategory" class="px-4 py-2 bg-gray-400 rounded hover:bg-gray-500">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
    @if($editingId)
    <div class="mb-6 p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Edit Category</h2>
        <form wire:submit.prevent="save" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name *</label>
                <input type="text" wire:model="name" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                <textarea wire:model="description" rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Update</button>
                <button type="button" wire:click="cancelEdit" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition">Cancel</button>
            </div>
        </form>
    </div>
    @endif

    <!-- Categories Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Projects</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($categories as $category)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $category->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $category->slug }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $category->projects_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $category->order }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="edit({{ $category->id }})" 
                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                Edit
                            </button>
                            <button wire:click="delete({{ $category->id }})" 
                                onclick="return confirm('Are you sure?')"
                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No categories found. Create your first category above.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
