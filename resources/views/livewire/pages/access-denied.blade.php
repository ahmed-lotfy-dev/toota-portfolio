<x-layouts.landing title="{{ __('Access Denied') }}">
    <div class="w-full min-h-screen flex flex-col bg-[#FDFCF8]">
        <x-landing.nav />

        <!-- Access Denied Content -->
        <div class="flex-1 flex items-center justify-center px-6 md:px-12 py-24">
            <div class="max-w-2xl w-full text-center space-y-8">
                
                <!-- Icon -->
                <div class="flex justify-center">
                    <div class="w-24 h-24 border-2 border-stone-900 flex items-center justify-center bg-transparent relative">
                        <svg class="w-12 h-12 text-stone-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        <!-- Diagonal Line -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-32 h-px bg-stone-900 rotate-45"></div>
                        </div>
                    </div>
                </div>

                <!-- Headline -->
                <div class="space-y-4">
                    <h1 class="text-5xl md:text-6xl font-bold text-stone-900 tracking-tight" style="font-family: 'Playfair Display', serif;">
                        {{ __('Access Denied') }}
                    </h1>
                    <div class="w-24 h-px bg-stone-900 mx-auto"></div>
                </div>

                <!-- Message -->
                <div class="space-y-6">
                    <p class="text-lg md:text-xl text-stone-600 leading-relaxed max-w-lg mx-auto">
                        {{ __("We appreciate your interest, but this portfolio's administrative area is restricted to the owner only.") }}
                    </p>
                    
                    <p class="text-sm uppercase tracking-[0.15em] font-medium text-stone-400">
                        {{ __('Only authorized administrators can sign in with Google') }}
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
                    
                    <a href="/login" 
                       class="inline-block px-8 py-4 border-2 border-stone-900 text-stone-900 text-xs uppercase tracking-[0.2em] font-medium hover:bg-stone-900 hover:text-[#FDFCF8] transition-all duration-300 min-w-[200px]">
                        {{ __('Go to Login') }}
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
