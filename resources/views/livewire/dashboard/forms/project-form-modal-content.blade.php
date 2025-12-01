<div>
    <form wire:submit.prevent="save" enctype="multipart/form-data" class="space-y-4">
        <div>
            <x-ui.input label="Title *" name="form.title" wire:model="form.title" />
        </div>
        <div>
            <x-ui.select label="Category *" name="form.category_id" wire:model="form.category_id">
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </x-ui.select>
        </div>
        <div>
            <x-ui.textarea label="Description" name="form.description" wire:model="form.description" />
        </div>
        <div class="flex gap-4">
            <x-ui.checkbox label="Featured" name="form.is_featured" wire:model="form.is_featured" />
            <x-ui.checkbox label="Published" name="form.is_published" wire:model="form.is_published" />
        </div>
        <div>
            <livewire:components.image-uploader wire:model="form.newImages" :context="$form->title" />
            @error('form.newImages')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>

        {{-- Display existing images --}}
        @if ($form->project && $form->project->images->isNotEmpty())
            <div class="mt-4 grid grid-cols-3 gap-4">
                @foreach ($form->project->images as $image)
                    <div class="relative group">
                        <img src="{{ $image->url }}" alt="Project Image" class="w-full h-32 object-cover rounded">
                        @if($image->is_primary)
                            <span
                                class="absolute top-1 left-1 px-2 py-0.5 bg-blue-500 text-white text-xs rounded-full shadow-sm">Primary</span>
                        @else
                            <button type="button" wire:click="setPrimary({{ $image->id }})"
                                class="absolute top-1 left-1 px-2 py-0.5 bg-gray-600/80 hover:bg-blue-500 text-white text-xs rounded-full shadow-sm transition-colors">
                                Set Primary
                            </button>
                        @endif
                        <button type="button" wire:click="removeImage({{ $image->id }})"
                            onclick="return confirm('Are you sure you want to delete this image? This action cannot be undone.');"
                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm shadow-md hover:bg-red-600 transition-colors">
                            &times;
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
        <div class="flex gap-2">
            <x-ui.button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed">
                <span wire:loading.remove wire:target="save">{{ $form->project ? 'Save Changes' : 'Add' }}</span>
                <span wire:loading wire:target="save">Saving...</span>
            </x-ui.button>
            <x-ui.button variant="secondary" wire:click="$dispatch('close-modal')">
                Cancel
            </x-ui.button>
        </div>
    </form>
</div>