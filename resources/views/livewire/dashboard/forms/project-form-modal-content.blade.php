<div>
        <form wire:submit.prevent="save" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label class="block mb-1">Title *</label>
            <input type="text" wire:model="form.title"
                class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white @error('form.title') border-red-500 @enderror">
            @error('form.title')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block mb-1">Category *</label>
            <div class="flex gap-2">
                <select wire:model="form.category_id"
                    class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white @error('form.category_id') border-red-500 @enderror">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>

            </div>
            @error('form.category_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block mb-1">Description</label>
            <textarea wire:model="form.description"
                class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white"></textarea>
        </div>
        <div class="flex gap-4">
            <label class="flex items-center">
                <input type="checkbox" wire:model="form.is_featured" class="mr-2">
                <span class="text-sm">Featured</span>
            </label>
            <label class="flex items-center">
                <input type="checkbox" wire:model="form.is_published" class="mr-2">
                <span class="text-sm">Published</span>
            </label>
        </div>
        <div>
            <livewire:components.image-uploader wire:model="form.newImages" />
            @error('form.newImages')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>

        {{-- Display existing images --}}
        @if ($form->project && $form->project->images->isNotEmpty())
            <div class="mt-4 grid grid-cols-3 gap-4">
                @foreach ($form->project->images as $image)
                    <div class="relative group">
                        <img src="{{ $image->url }}" alt="Project Image" class="w-full h-32 object-cover rounded">
                        @if($image->is_primary)
                        <span class="absolute top-1 left-1 px-2 py-0.5 bg-blue-500 text-white text-xs rounded-full shadow-sm">Primary</span>
                        @else
                        <button type="button" wire:click="setPrimary({{ $image->id }})" 
                            class="absolute top-1 left-1 px-2 py-0.5 bg-gray-600/80 hover:bg-blue-500 text-white text-xs rounded-full shadow-sm transition-colors">
                            Set Primary
                        </button>
                        @endif
                        <button type="button" wire:click="removeImage({{ $image->id }})" onclick="return confirm('Are you sure you want to delete this image? This action cannot be undone.');"
                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm shadow-md hover:bg-red-600 transition-colors">
                            &times;
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
        <div class="flex gap-2">
            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                <span wire:loading.remove wire:target="save">{{ $form->project ? 'Save Changes' : 'Add' }}</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
            <button type="button" wire:click="$dispatch('close-modal')"
                class="px-4 py-2 bg-gray-400 rounded hover:bg-gray-500">Cancel</button>
        </div>
    </form>
</div>
