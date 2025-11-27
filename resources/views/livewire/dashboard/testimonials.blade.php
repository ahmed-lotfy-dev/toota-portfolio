<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Testimonials</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage client testimonials</p>
        </div>
        <button wire:click="createTestimonial" class="h-10 px-5 bg-blue-500 text-white font-bold rounded hover:bg-blue-700">Add Testimonial</button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md dark:bg-green-900/20 dark:border-green-800 dark:text-green-400">
            {{ session('message') }}
        </div>
    @endif

    <!-- Testimonials Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Testimonial</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Published</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($testimonials as $testimonial)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $testimonial->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $testimonial->title }}
                        </td>
                        <td class="px-6 py-4 max-w-xs overflow-hidden text-ellipsis whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $testimonial->body }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button wire:click="togglePublished({{ $testimonial->id }})" 
                                class="px-2 py-1 text-xs rounded {{ $testimonial->is_published ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                {{ $testimonial->is_published ? 'Published' : 'Draft' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="edit({{ $testimonial->id }})" 
                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                Edit
                            </button>
                            <button wire:click="delete({{ $testimonial->id }})" 
                                onclick="return confirm('Are you sure you want to delete this testimonial? This action cannot be undone.');"
                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No testimonials found. Click "Add Testimonial" to get started!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-30">
        <div class="bg-white dark:bg-gray-800 rounded shadow-lg p-6 w-full max-w-md relative">
            <button wire:click="resetInput" class="absolute top-3 right-3 text-2xl text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">&times;</button>
            <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">
                {{ $isEditing ? 'Edit Testimonial' : 'Add New Testimonial' }}
            </h3>
            <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name *</label>
                    <input type="text" wire:model.defer="name" 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror">
                    @error('name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title (Optional)</label>
                    <input type="text" wire:model.defer="title" 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('title') border-red-500 @enderror">
                    @error('title')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Testimonial *</label>
                    <textarea wire:model.defer="body" rows="3" 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('body') border-red-500 @enderror"></textarea>
                    @error('body')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                </div>
                <div class="flex items-center">
                    <input type="checkbox" wire:model.defer="is_published" id="is_published" class="mr-2">
                    <label for="is_published" class="text-sm text-gray-700 dark:text-gray-300">Published</label>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        {{ $isEditing ? 'Update' : 'Save' }}
                    </button>
                    <button type="button" wire:click="resetInput" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
