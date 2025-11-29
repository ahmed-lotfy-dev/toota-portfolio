@props(['name', 'title'])

<div x-data="{ show : false , name : '{{ $name }}' }" x-show="show"
    x-on:open-modal.window="show = ($event.detail.name === name)" x-on:close-modal.window="show = false"
    x-on:keydown.escape.window="show = false" style="display:none;" class="fixed z-50 inset-0" x-transition.duration>

    {{-- Gray Background --}}
    <div x-on:click="show = false" class="fixed inset-0 bg-stone-900/60 backdrop-blur-sm transition-opacity"></div>

    {{-- Modal Body --}}
    <div class="bg-[#FDFCF8] dark:bg-stone-900 rounded-xl m-auto fixed inset-0 max-w-2xl overflow-y-auto shadow-2xl transform transition-all" style="max-height:fit-content; margin-top: 10vh; margin-bottom: 10vh;">
        @if (isset($title))
            <div class="px-6 py-4 flex items-center justify-between border-b border-stone-200 dark:border-stone-800">
                <div class="text-xl font-bold text-stone-900 dark:text-stone-100 font-heading tracking-tight">{{ $title }}</div>
                <button x-on:click="$dispatch('close-modal')" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif
        <div class="p-6 font-paragraph text-stone-600 dark:text-stone-300">
            {{ $body }}
        </div>
    </div>
</div>