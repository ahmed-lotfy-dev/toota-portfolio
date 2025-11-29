<!-- resources/views/components/landing/process.blade.php -->
<section id="process" class="w-full bg-[#FDFCF8] py-24 px-6 md:px-12">
    <div class="max-w-4xl mx-auto text-center">
        <span class="text-stone-500 uppercase tracking-[0.2em] text-xs font-medium mb-2 block">{{ __('process.subtitle') }}</span>
        <h2 class="text-4xl md:text-5xl text-stone-900 leading-tight mb-16" ">
            {{ __('process.title') }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
            <!-- Connector Line -->
            <div class="absolute top-1/2 -translate-y-1/2 left-0 w-full h-px hidden md:block">
                <div class="w-full h-px border-t border-dashed border-stone-300 -mt-4"></div>
            </div>

            <!-- Step 1 -->
            <div class="relative z-10 flex flex-col items-center text-center md:border-l-2 md:border-dotted">
                <div class="w-16 h-16 rounded-full bg-white border-2 border-gray-300 flex items-center justify-center text-xl font-serif text-stone-600 md:mb-10 my-10" ">01</div>
                <h3 class="text-lg font-semibold text-stone-800 mb-7">{{ __('process.step1_title') }}</h3>
                <p class="text-stone-600 text-sm leading-relaxed px-3">
                    {{ __('process.step1_description') }}
                </p>
            </div>

            <!-- Step 2 -->
            <div class="relative z-10 flex flex-col items-center text-center md:border-l-2 md:border-dotted ">
                <div class="w-16 h-16 rounded-full bg-white border-2 border-gray-300 flex items-center justify-center text-xl font-serif text-stone-600 md:mb-10 my-10" ">02</div>
                <h3 class="text-lg font-semibold text-stone-800 mb-7">{{ __('process.step2_title') }}</h3>
                <p class="text-stone-600 text-sm leading-relaxed px-3">
                    {{ __('process.step2_description') }}
                </p>
            </div>

            <!-- Step 3 -->
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-full bg-white border-2 border-gray-300 flex items-center justify-center text-xl font-serif text-stone-600 md:mb-10 my-10" ">03</div>
                <h3 class="text-lg font-semibold text-stone-800 mb-7">{{ __('process.step3_title') }}</h3>
                <p class="text-stone-600 text-sm leading-relaxed px-3">
                    {{ __('process.step3_description') }}
                </p>
            </div>
        </div>
    </div>
</section>
