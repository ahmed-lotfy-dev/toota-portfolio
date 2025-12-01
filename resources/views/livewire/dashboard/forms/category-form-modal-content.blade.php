<div>
    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <x-ui.input label="Name *" name="form.name" wire:model="form.name" />
        </div>

        <div>
            <x-ui.textarea label="Description" name="form.description" wire:model="form.description" />
        </div>

        <div class="flex gap-2">
            <x-ui.button type="submit">
                {{ $form->category ? 'Save Changes' : 'Add' }}
            </x-ui.button>

            <x-ui.button variant="secondary" type="button" @click="$dispatch('close-modal')">
                Cancel
            </x-ui.button>
        </div>
    </form>
</div>