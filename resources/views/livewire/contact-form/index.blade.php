<section class="relative py-20 px-4 sm:px-6 lg:px-8 overflow-hidden bg-[#FDFCF8]">
    {{-- Decorative background elements - Artisan aesthetic --}}
    <div class="absolute inset-0 -z-10">
        <div class="absolute top-40 right-20 w-96 h-96 bg-orange-100/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-40 left-20 w-96 h-96 bg-amber-100/15 rounded-full blur-3xl"></div>
    </div>

    {{-- Noise texture overlay --}}
    <div class="absolute inset-0 -z-10 opacity-[0.02]"
        style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noise%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noise)%22 opacity=%221%22/%3E%3C/svg%3E');">
    </div>

    <div class="max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="text-center mb-16">
            <span class="inline-block py-2 px-4 border border-stone-300 rounded-full text-xs font-medium tracking-widest text-stone-500 uppercase mb-8 bg-white/50 backdrop-blur-sm">
                {{ __('contact.label') }}
            </span>

            <h2 class="text-5xl md:text-6xl lg:text-7xl text-stone-900 mb-6 leading-tight tracking-tight" style="font-family: 'Playfair Display', serif;">
                {{ __('contact.title') }}
            </h2>

            <div class="w-16 h-1 bg-stone-400 rounded-full mx-auto mb-8"></div>

            <p class="text-lg md:text-xl text-stone-600 max-w-2xl mx-auto leading-relaxed font-light">
                {{ __('contact.subtitle') }}
            </p>
        </div>

        {{-- Contact Form Card --}}
        <div class="relative group mb-16">
            <div class="absolute -inset-0.5 bg-linear-to-r from-stone-300 via-stone-200 to-stone-300 rounded-lg blur opacity-10 group-hover:opacity-20 transition duration-500"></div>

            <div class="relative bg-white rounded-lg shadow-lg p-8 md:p-12 border border-stone-100">
                {{-- Success Message --}}
                @if (session()->has('message'))
                    <div class="mb-8 p-4 bg-stone-50 border-l-4 border-stone-400 rounded">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-stone-800 rounded flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-stone-700 font-medium">{{ session('message') }}</p>
                        </div>
                    </div>
                @endif

                <form wire:submit.prevent="submitForm" class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        {{-- Name Field --}}
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-semibold text-stone-900">
                                {{ __('contact.name.label') }}
                                <span class="text-stone-400">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <input type="text" id="name" wire:model.defer="name"
                                    placeholder="{{ __('contact.name.placeholder') }}"
                                    class="w-full pl-12 pr-4 py-3.5 bg-stone-50 border border-stone-200 rounded text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-stone-800 focus:border-transparent transition duration-300">
                            </div>
                            @error('name')
                                <p class="text-stone-600 text-xs mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Email Field --}}
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-semibold text-stone-900">
                                {{ __('contact.email.label') }}
                                <span class="text-stone-400">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <input type="email" id="email" wire:model.defer="email"
                                    placeholder="{{ __('contact.email.placeholder') }}"
                                    class="w-full pl-12 pr-4 py-3.5 bg-stone-50 border border-stone-200 rounded text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-stone-800 focus:border-transparent transition duration-300">
                            </div>
                            @error('email')
                                <p class="text-stone-600 text-xs mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Phone Field --}}
                    <div class="space-y-2">
                        <label for="phone" class="block text-sm font-semibold text-stone-900">
                            {{ __('contact.phone.label') }}
                            <span class="text-stone-400 text-xs font-normal">({{ __('contact.optional') }})</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <input type="text" id="phone" wire:model.defer="phone"
                                placeholder="{{ __('contact.phone.placeholder') }}"
                                class="w-full pl-12 pr-4 py-3.5 bg-stone-50 border border-stone-200 rounded text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-stone-800 focus:border-transparent transition duration-300">
                        </div>
                        @error('phone')
                            <p class="text-stone-600 text-xs mt-1 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Message Field --}}
                    <div class="space-y-2">
                        <label for="message" class="block text-sm font-semibold text-stone-900">
                            {{ __('contact.message.label') }}
                            <span class="text-stone-400">*</span>
                        </label>
                        <div class="relative">
                            <textarea id="message" wire:model.defer="message" rows="6"
                                placeholder="{{ __('contact.message.placeholder') }}"
                                class="w-full px-4 py-3.5 bg-stone-50 border border-stone-200 rounded text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-stone-800 focus:border-transparent transition duration-300 resize-none"></textarea>
                        </div>
                        @error('message')
                            <p class="text-stone-600 text-xs mt-1 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-4">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-8 py-4 bg-stone-900 text-[#FDFCF8] text-sm tracking-widest uppercase font-medium rounded shadow-lg hover:shadow-xl hover:bg-stone-800 transform hover:-translate-y-1 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>{{ __('contact.send_message') }}</span>
                            <span wire:loading>{{ __('contact.sending') }}</span>
                            <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                            <svg wire:loading class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Contact Info Cards --}}
        <div class="grid md:grid-cols-3 gap-6 mt-12">
            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-lg shadow-md text-center border border-stone-100 hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 bg-stone-800 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-stone-900 mb-2" style="font-family: 'Playfair Display', serif;">{{ __('contact.email_us') }}</h3>
                <a href="mailto:info@totaart.com" class="text-stone-600 hover:text-stone-900 transition">info@totaart.com</a>
            </div>

            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-lg shadow-md text-center border border-stone-100 hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 bg-stone-800 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-stone-900 mb-2" style="font-family: 'Playfair Display', serif;">{{ __('contact.call_us') }}</h3>
                <a href="tel:+201012345678" class="text-stone-600 hover:text-stone-900 transition">+20 101 234 5678</a>
            </div>

            <div class="bg-white/80 backdrop-blur-sm p-8 rounded-lg shadow-md text-center border border-stone-100 hover:shadow-lg transition duration-300">
                <div class="w-12 h-12 bg-stone-800 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-stone-900 mb-2" style="font-family: 'Playfair Display', serif;">{{ __('contact.visit_us') }}</h3>
                <p class="text-stone-600">{{ __('contact.address') }}</p>
            </div>
        </div>
    </div>
</section>