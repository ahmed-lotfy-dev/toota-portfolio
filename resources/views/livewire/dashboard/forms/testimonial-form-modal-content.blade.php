<div>
    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <x-ui.input label="Name *" name="form.name" wire:model="form.name" />
        </div>
        <div>
            <x-ui.input label="Title (Optional)" name="form.title" wire:model="form.title" />
        </div>
        <div>
            <x-ui.textarea label="Testimonial *" name="form.body" wire:model="form.body" rows="3" />
        </div>
        <div>
            <x-ui.checkbox label="Published" name="form.is_published" wire:model="form.is_published" />
        </div>
        <div class="flex gap-2">
            <x-ui.button type="submit">
                {{ $form->testimonial ? 'Save Changes' : 'Add' }}
            </x-ui.button>
            <x-ui.button variant="secondary" type="button" wire:click="$dispatch('close-modal')">
                Cancel
            </x-ui.button>
        </div>
    </form>
</div>