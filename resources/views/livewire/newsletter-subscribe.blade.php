<div>
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md">
            {{ session('message') }}
        </div>
    @else
        <form wire:submit.prevent="subscribe" class="space-y-4">
            <div class="relative">
                <input type="email" 
                       wire:model.defer="email"
                       placeholder="{{ __('footer.newsletter.placeholder') }}" 
                       class="w-full px-5 py-4 bg-stone-800 border border-transparent text-[#FDFCF8] placeholder-stone-600 focus:outline-none focus:bg-stone-700 focus:border-stone-500 transition-all duration-300 @error('email') border-red-500 @enderror">
                
                @error('email')
                    <p class="absolute -bottom-6 left-0 text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="w-full px-8 py-4 bg-[#FDFCF8] text-stone-900 text-xs uppercase tracking-widest font-bold hover:bg-stone-300 transition-colors duration-300 disabled:opacity-50" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ __('footer.newsletter.subscribe') }}</span>
                <span wire:loading>Subscribing...</span>
            </button>
        </form>
    @endif
</div>
