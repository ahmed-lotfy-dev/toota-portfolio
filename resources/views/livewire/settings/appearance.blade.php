<section class="w-full p-8">
    <flux:heading>{{ __('Appearance') }}</flux:heading>
    <flux:subheading>{{ __('Update the appearance settings for your account') }}</flux:subheading>

    <div class="mt-5 w-full max-w-lg">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
        </flux:radio.group>
    </div>
</section>
