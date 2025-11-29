<!-- resources/views/components/landing/faq.blade.php -->
<section id="faq" class="w-full bg-[#FDFCF8] py-24 px-6 md:px-12">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16">
            <span class="text-stone-500 uppercase tracking-[0.2em] text-xs font-medium mb-2 block">{{ __('faq.subtitle') }}</span>
            <h2 class="text-4xl md:text-5xl text-stone-900 leading-tight" style="font-family: 'Playfair Display', serif;">
                {{ __('faq.title') }}
            </h2>
        </div>

        <div x-data="{ open: 1 }" class="space-y-4">

            <!-- FAQ Item 1 -->
            <div class="border-b border-stone-200 pb-4">
                <button @click="open = (open === 1 ? null : 1)" class="w-full flex justify-between items-center text-left">
                    <h3 class="text-lg font-medium text-stone-800">{{ __('faq.q1') }}</h3>
                    <span x-show="open !== 1" class="text-stone-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </span>
                    <span x-show="open === 1" class="text-stone-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                    </span>
                </button>
                <div x-show="open === 1" x-collapse class="pt-4">
                    <p class="text-stone-600 text-sm leading-relaxed">
                        {{ __('faq.a1') }}
                    </p>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="border-b border-stone-200 pb-4">
                <button @click="open = (open === 2 ? null : 2)" class="w-full flex justify-between items-center text-left">
                    <h3 class="text-lg font-medium text-stone-800">{{ __('faq.q2') }}</h3>
                    <span x-show="open !== 2" class="text-stone-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </span>
                    <span x-show="open === 2" class="text-stone-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                    </span>
                </button>
                <div x-show="open === 2" x-collapse class="pt-4">
                    <p class="text-stone-600 text-sm leading-relaxed">
                        {{ __('faq.a2') }}
                    </p>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="border-b border-stone-200 pb-4">
                <button @click="open = (open === 3 ? null : 3)" class="w-full flex justify-between items-center text-left">
                    <h3 class="text-lg font-medium text-stone-800">{{ __('faq.q3') }}</h3>
                    <span x-show="open !== 3" class="text-stone-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </span>
                    <span x-show="open === 3" class="text-stone-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                    </span>
                </button>
                <div x-show="open === 3" x-collapse class="pt-4">
                    <p class="text-stone-600 text-sm leading-relaxed">
                        {{ __('faq.a3') }}
                    </p>
                </div>
            </div>

             <!-- FAQ Item 4 -->
             <div class="border-b border-stone-200 pb-4">
                <button @click="open = (open === 4 ? null : 4)" class="w-full flex justify-between items-center text-left">
                    <h3 class="text-lg font-medium text-stone-800">{{ __('faq.q4') }}</h3>
                    <span x-show="open !== 4" class="text-stone-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </span>
                    <span x-show="open === 4" class="text-stone-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                    </span>
                </button>
                <div x-show="open === 4" x-collapse class="pt-4">
                    <p class="text-stone-600 text-sm leading-relaxed">
                        {{ __('faq.a4') }}
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>
