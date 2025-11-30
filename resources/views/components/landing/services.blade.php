<section id="services" class="relative py-20 px-4 sm:px-6 lg:px-8 overflow-hidden bg-[#FDFCF8]">
    {{-- Decorative background elements - Artisan aesthetic --}}
    <div class="absolute inset-0 -z-10">
        <div class="absolute top-40 left-20 w-72 h-72 bg-orange-100/15 rounded-full blur-3xl"></div>
        <div class="absolute bottom-40 right-20 w-96 h-96 bg-amber-100/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-stone-200/5 rounded-full blur-3xl"></div>
    </div>

    {{-- Noise texture overlay --}}
    <div class="absolute inset-0 -z-10 opacity-[0.02]"
        style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noise%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noise)%22 opacity=%221%22/%3E%3C/svg%3E');">
    </div>

    <div class="max-w-7xl mx-auto">
        {{-- Section Header --}}
        <div class="text-center mb-16">
            <span class="inline-block py-2 px-4 border border-stone-300 rounded-full text-xs font-medium tracking-widest text-stone-500 uppercase mb-8 bg-white/50 backdrop-blur-sm">
                {{ __('services.label') }}
            </span>

            <h2 class="text-5xl md:text-6xl lg:text-7xl text-stone-900 mb-6 leading-tight tracking-tight" style="font-family: 'Playfair Display', serif;">
                {{ __('services.heading') }}
            </h2>

            <div class="w-16 h-1 bg-stone-400 rounded-full mx-auto mb-8"></div>

            <p class="text-lg md:text-xl text-stone-600 max-w-2xl mx-auto leading-relaxed font-light">
                {{ __('services.subheading') }}
            </p>
        </div>

        {{-- Services Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            {{-- Service 1: Escape Room Masks --}}
            <div class="group relative">
                <div class="absolute -inset-0.5 bg-linear-to-r from-stone-300 to-stone-200 rounded-lg blur opacity-10 group-hover:opacity-20 transition duration-500"></div>
                <div class="relative h-full bg-white p-8 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-stone-100">
                    <div class="w-14 h-14 bg-stone-800 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-stone-900 mb-3" style="font-family: 'Playfair Display', serif;">{{ __('services.escape_masks.title') }}</h3>
                    <p class="text-stone-600 leading-relaxed text-sm">{{ __('services.escape_masks.description') }}</p>

                    <div class="mt-6 flex items-center text-stone-700 font-medium group-hover:gap-2 transition-all duration-300">
                        <span class="text-xs tracking-wide uppercase">{{ __('services.learn_more') }}</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Service 2: Cinema & Theater Props --}}
            <div class="group relative">
                <div class="absolute -inset-0.5 bg-linear-to-r from-stone-300 to-stone-200 rounded-lg blur opacity-10 group-hover:opacity-20 transition duration-500"></div>
                <div class="relative h-full bg-white p-8 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-stone-100">
                    <div class="w-14 h-14 bg-stone-800 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-stone-900 mb-3" style="font-family: 'Playfair Display', serif;">{{ __('services.cinema_props.title') }}</h3>
                    <p class="text-stone-600 leading-relaxed text-sm">{{ __('services.cinema_props.description') }}</p>

                    <div class="mt-6 flex items-center text-stone-700 font-medium group-hover:gap-2 transition-all duration-300">
                        <span class="text-xs tracking-wide uppercase">{{ __('services.learn_more') }}</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Service 3: Custom Costumes --}}
            <div class="group relative">
                <div class="absolute -inset-0.5 bg-linear-to-r from-stone-300 to-stone-200 rounded-lg blur opacity-10 group-hover:opacity-20 transition duration-500"></div>
                <div class="relative h-full bg-white p-8 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-stone-100">
                    <div class="w-14 h-14 bg-stone-800 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-stone-900 mb-3" style="font-family: 'Playfair Display', serif;">{{ __('services.costumes.title') }}</h3>
                    <p class="text-stone-600 leading-relaxed text-sm">{{ __('services.costumes.description') }}</p>

                    <div class="mt-6 flex items-center text-stone-700 font-medium group-hover:gap-2 transition-all duration-300">
                        <span class="text-xs tracking-wide uppercase">{{ __('services.learn_more') }}</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Service 4: Educational Panels --}}
            <div class="group relative">
                <div class="absolute -inset-0.5 bg-linear-to-r from-stone-300 to-stone-200 rounded-lg blur opacity-10 group-hover:opacity-20 transition duration-500"></div>
                <div class="relative h-full bg-white p-8 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-stone-100">
                    <div class="w-14 h-14 bg-stone-800 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-stone-900 mb-3" style="font-family: 'Playfair Display', serif;">{{ __('services.educational_panels.title') }}</h3>
                    <p class="text-stone-600 leading-relaxed text-sm">{{ __('services.educational_panels.description') }}</p>

                    <div class="mt-6 flex items-center text-stone-700 font-medium group-hover:gap-2 transition-all duration-300">
                        <span class="text-xs tracking-wide uppercase">{{ __('services.learn_more') }}</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Service 5: Other Handmade Services --}}
            <div class="group relative">
                <div class="absolute -inset-0.5 bg-linear-to-r from-stone-300 to-stone-200 rounded-lg blur opacity-10 group-hover:opacity-20 transition duration-500"></div>
                <div class="relative h-full bg-white p-8 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-stone-100">
                    <div class="w-14 h-14 bg-stone-800 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-stone-900 mb-3" style="font-family: 'Playfair Display', serif;">{{ __('services.other.title') }}</h3>
                    <p class="text-stone-600 leading-relaxed text-sm">{{ __('services.other.description') }}</p>

                    <div class="mt-6 flex items-center text-stone-700 font-medium group-hover:gap-2 transition-all duration-300">
                        <span class="text-xs tracking-wide uppercase">{{ __('services.learn_more') }}</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Featured CTA Card --}}
            <div class="group relative">
                <div class="absolute -inset-0.5 bg-linear-to-r from-stone-800 to-stone-600 rounded-lg blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
                <div class="relative h-full bg-stone-900 p-8 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col items-center justify-center text-center border border-stone-700">
                    <div class="w-14 h-14 bg-white/10 backdrop-blur-sm rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-semibold text-white mb-3" style="font-family: 'Playfair Display', serif;">{{ __('services.custom_project.title') }}</h3>
                    <p class="text-stone-200 leading-relaxed mb-6 text-sm">{{ __('services.custom_project.description') }}</p>

                    <a href="/" class="inline-flex items-center gap-2 px-6 py-3 bg-stone-100 text-stone-900 font-semibold rounded shadow-md hover:shadow-lg transform hover:scale-105 transition duration-300 text-sm tracking-wide uppercase">
                        <span>{{ __('services.contact_us') }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>