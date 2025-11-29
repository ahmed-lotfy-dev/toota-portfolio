<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Categories</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage your project categories</p>
        </div>
        <button type="button" wire:click="showModal" class="h-10 px-5 bg-blue-500 text-white font-bold rounded hover:bg-blue-700">Add Category</button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md dark:bg-green-900/20 dark:border-green-800 dark:text-green-400">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md dark:bg-red-900/20 dark:border-red-800 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    <!-- Unified Add/Edit Modal -->
    <div x-data="{ show: @entangle('showCategoryModal') }"
         x-show="show"
         x-on:keydown.escape.window="show = false"
         style="display: none;"
         class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="show = false"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md mx-auto my-8" @click.stop>
            <button wire:click="hideModal" class="absolute top-3 right-3 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white text-2xl">&times;</button>
            <h3 class="text-lg font-bold mb-4">{{ $form->category ? 'Edit Category' : 'Add New Category' }}</h3>
            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="block mb-1">Name *</label>
                    <input type="text" wire:model="form.name" class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white @error('form.name') border-red-500 @enderror">
                    @error('form.name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block mb-1">Description</label>
                    <textarea wire:model="form.description" class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white"></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">{{ $form->category ? 'Save Changes' : 'Add' }}</button>
                    <button type="button" wire:click="hideModal" class="px-4 py-2 bg-gray-400 rounded hover:bg-gray-500">Cancel</button>
                </div>
            </form>
        </div>
    </div>

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
                                onclick="return confirm('This action cannot be undone. Are you sure?')"
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
