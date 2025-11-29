<div>
    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label class="block mb-1">Name *</label>
            <input type="text" wire:model="form.name"
                class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white @error('form.name') border-red-500 @enderror">
            @error('form.name')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="block mb-1">Description</label>
            <textarea wire:model="form.description"
                class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white"></textarea>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                {{ $form->category ? 'Save Changes' : 'Add' }}
            </button>

            <button type="button" @click="$dispatch('close-modal')"
                class="px-4 py-2 bg-gray-400 rounded hover:bg-gray-500">
                Cancel
            </button>
        </div>
    </form>
</div>
