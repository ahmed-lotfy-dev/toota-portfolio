<div class="py-12 bg-gray-100">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">{{ __('messages.contact.title') }}</h2>

                <form wire:submit.prevent="submitForm" class="space-y-6">
                    <!-- Name Field -->
                    <div>gi
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            {{ __('messages.contact.name.label') }}
                        </label>
                        <input type="text" id="name" wire:model.defer="name"
                            placeholder="{{ __('messages.contact.name.placeholder') }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            {{ __('messages.contact.email.label') }}
                        </label>
                        <input type="email" id="email" wire:model.defer="email"
                            placeholder="{{ __('messages.contact.email.placeholder') }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Phone Number Field -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">
                            {{ __('messages.contact.phone.label') }}
                        </label>
                        <input type="text" id="phone" wire:model.defer="phone"
                            placeholder="{{ __('messages.contact.phone.placeholder') }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Message Field -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700">
                            {{ __('messages.contact.message.label') }}
                        </label>
                        <textarea id="message" wire:model.defer="message" rows="5"
                            placeholder="{{ __('messages.contact.message.placeholder') }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 resize-y"></textarea>
                        @error('message') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('messages.contact.send_message') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>