    <div class="w-full min-h-screen flex flex-col">
        <div class="flex-grow min-h-screen bg-gradient-to-br from-stone-50 to-stone-100 flex items-center justify-center px-6">
            <div class="max-w-2xl w-full text-center">
                
                {{-- Artistic 404 Illustration --}}
                <div class="mb-12 relative" data-aos="fade-down">
                    <div class="inline-block relative">
                        <h1 class="text-[12rem] md:text-[16rem] font-bold text-stone-200 leading-none select-none" style="font-family: 'Playfair Display', serif;">
                            404
                        </h1>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-32 h-32 md:w-40 md:h-40 text-stone-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="0.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="space-y-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="space-y-3">
                        <h2 class="text-3xl md:text-4xl font-bold text-stone-900" style="font-family: 'Playfair Display', serif;">
                            {{ __('Page Not Found') }}
                        </h2>
                        <div class="w-20 h-1 bg-stone-900 rounded-full mx-auto"></div>
                    </div>
                    
                    <p class="text-lg text-stone-600 max-w-md mx-auto">
                        {{ __('The page you\'re looking for doesn\'t exist or has been moved.') }}
                    </p>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-4 justify-center pt-8">
                        <a href="{{ route('home') }}" 
                           class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-stone-900 text-white uppercase tracking-widest text-sm shadow-xl hover:shadow-2xl hover:bg-stone-700 transform hover:-translate-y-0.5 transition duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>{{ __('Go Home') }}</span>
                        </a>

                        <button onclick="window.history.back()" 
                                class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white border-2 border-stone-900 text-stone-900 uppercase tracking-widest text-sm hover:bg-stone-900 hover:text-white transform hover:-translate-y-0.5 transition duration-300">
                            <span>{{ __('Go Back') }}</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
