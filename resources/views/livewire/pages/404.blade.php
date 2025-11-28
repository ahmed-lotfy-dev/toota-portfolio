<x-layouts.landing title="{{ __('Page Not Found') }}">
    <div class="w-full min-h-screen flex flex-col bg-[#FDFCF8]">
        <x-landing.nav />

        <!-- 404 Content -->
        <div class="flex-1 flex items-center justify-center px-6 md:px-12 py-24">
            <div class="max-w-2xl w-full text-center space-y-8">
                
                <!-- Icon -->
                <div class="flex justify-center">
                    <div class="w-24 h-24 border-2 border-stone-900 flex items-center justify-center bg-transparent relative">
                        <svg class="w-12 h-12 text-stone-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                        </svg>
                    </div>
                </div>

                <!-- 404 Number -->
                <div class="space-y-2">
                    <h2 class="text-8xl md:text-9xl font-bold text-stone-900 tracking-tight" style="font-family: 'Playfair Display', serif;">
                        404
                    </h2>
                </div>

                <!-- Headline -->
                <div class="space-y-4">
                    <h1 class="text-4xl md:text-5xl font-bold text-stone-900 tracking-tight" style="font-family: 'Playfair Display', serif;">
                        {{ __('Page Not Found') }}
                    </h1>
                    <div class="w-24 h-px bg-stone-900 mx-auto"></div>
                </div>

                <!-- Message -->
                <div class="space-y-6">
                    <p class="text-lg md:text-xl text-stone-600 leading-relaxed max-w-lg mx-auto">
                        {{ __("The page you're looking for doesn't exist or has been moved.") }}
                    </p>
                    
                    <p class="text-sm uppercase tracking-[0.15em] font-medium text-stone-400">
                        {{ __('Let\'s get you back on track') }}
                    </p>
                </div>

                <!-- Divider -->
                <div class="py-8">
                    <div class="w-full max-w-xs mx-auto h-px bg-stone-200"></div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="/" 
                       class="inline-block px-8 py-4 bg-stone-900 text-[#FDFCF8] text-xs uppercase tracking-[0.2em] font-medium hover:bg-stone-700 transition-colors duration-300 min-w-[200px]">
                        {{ __('Return Home') }}
                    </a>
                    
                    <a href="javascript:history.back()" 
                       class="inline-block px-8 py-4 border-2 border-stone-900 text-stone-900 text-xs uppercase tracking-[0.2em] font-medium hover:bg-stone-900 hover:text-[#FDFCF8] transition-all duration-300 min-w-[200px]">
                        {{ __('Go Back') }}
                    </a>
                </div>

                <!-- Footer Note -->
                <p class="text-xs text-stone-400 tracking-wide pt-8">
                    {{ __('If you believe this is an error, please contact the site administrator.') }}
                </p>
            </div>
        </div>

        <x-landing.footer />
    </div>
</x-layouts.landing>
