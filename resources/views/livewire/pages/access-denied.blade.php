<x-layouts.landing>
    <div class="w-full min-h-screen flex flex-col">
        <div class="flex-grow min-h-screen bg-gradient-to-br from-stone-50 to-stone-100 flex items-center justify-center px-6">
            <div class="max-w-2xl w-full text-center">
                
                {{-- Icon/Illustration --}}
                <div class="mb-12" data-aos="fade-down">
                    <div class="inline-flex items-center justify-center w-32 h-32 bg-stone-200 rounded-full">
                        <svg class="w-16 h-16 text-stone-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                </div>

                {{-- Content --}}
                <div class="space-y-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="space-y-3">
                        <h2 class="text-3xl md:text-4xl font-bold text-stone-900" style="font-family: 'Playfair Display', serif;">
                            {{ __('Access Denied') }}
                        </h2>
                        <div class="w-20 h-1 bg-stone-900 rounded-full mx-auto"></div>
                    </div>
                    
                    <p class="text-lg text-stone-600 max-w-md mx-auto">
                        {{ __('We couldn\'t complete your authentication request. This may be because you denied access or there was an error with the authentication provider.') }}
                    </p>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-4 justify-center pt-8">
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-stone-900 text-white uppercase tracking-widest text-sm shadow-xl hover:shadow-2xl hover:bg-stone-700 transform hover:-translate-y-0.5 transition duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>{{ __('Try Again') }}</span>
                        </a>

                        <a href="{{ route('home') }}" 
                           class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white border-2 border-stone-900 text-stone-900 uppercase tracking-widest text-sm hover:bg-stone-900 hover:text-white transform hover:-translate-y-0.5 transition duration-300">
                            <span>{{ __('Go Home') }}</span>
                        </a>
                    </div>

                    {{-- Help Text --}}
                    <div class="pt-8">
                        <p class="text-sm text-stone-500">
                            {{ __('If you continue to experience issues, please contact support.') }}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layouts.landing>
