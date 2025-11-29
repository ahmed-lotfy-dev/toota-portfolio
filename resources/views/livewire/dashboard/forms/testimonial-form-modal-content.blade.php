<div>
    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name *</label>
            <input type="text" wire:model="form.name" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('form.name') border-red-500 @enderror">
            @error('form.name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title (Optional)</label>
            <input type="text" wire:model="form.title" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('form.title') border-red-500 @enderror">
            @error('form.title')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Testimonial *</label>
            <textarea wire:model="form.body" rows="3" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('form.body') border-red-500 @enderror"></textarea>
            @error('form.body')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="flex items-center">
            <input type="checkbox" wire:model="form.is_published" id="is_published" class="mr-2">
            <label for="is_published" class="text-sm text-gray-700 dark:text-gray-300">Published</label>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                {{ $form->testimonial ? 'Save Changes' : 'Add' }}
            </button>
            <button type="button" wire:click="$dispatch('close-modal')" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                Cancel
            </button>
        </div>
    </form>
</div>
