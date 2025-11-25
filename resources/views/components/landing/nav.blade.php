<nav class="fixed w-full z-50 bg-[#FDFCF8]/90 backdrop-blur-sm border-b border-stone-100 transition-all duration-300"
    x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-6 md:px-12">
        <div class="flex justify-between items-center h-24">

            <div class="shrink-0">
                <a href="/" class="flex items-center gap-3 group">
                    <div
                        class="w-10 h-10 border border-stone-900 flex items-center justify-center bg-transparent group-hover:bg-stone-900 transition-colors duration-500">
                        <svg class="w-5 h-5 text-stone-900 group-hover:text-[#FDFCF8] transition-colors duration-500"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a16.001 16.001 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-stone-900 tracking-tight"
                        style="font-family: 'Playfair Display', serif;">
                        {{ __('nav.brand') }}
                    </span>
                </a>
            </div>

            <div class="hidden md:flex items-center gap-8 rtl:space-x-reverse">
                <a href="/"
                    class="text-xs uppercase tracking-[0.15em] font-medium text-stone-500 hover:text-stone-900 transition-colors duration-300 relative group">
                    {{ __('nav.home') }}
                    <span
                        class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-0 h-px bg-stone-900 transition-all duration-300 group-hover:w-full"></span>
                </a>

                <a href="/projects"
                    class="text-xs uppercase tracking-[0.15em] font-medium text-stone-500 hover:text-stone-900 transition-colors duration-300 relative group">
                    {{ __('nav.projects') }}
                    <span
                        class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-0 h-px bg-stone-900 transition-all duration-300 group-hover:w-full"></span>
                </a>

                <a href="/services"
                    class="text-xs uppercase tracking-[0.15em] font-medium text-stone-500 hover:text-stone-900 transition-colors duration-300 relative group">
                    {{ __('nav.services') }}
                    <span
                        class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-0 h-px bg-stone-900 transition-all duration-300 group-hover:w-full"></span>
                </a>

                <a href="/about"
                    class="text-xs uppercase tracking-[0.15em] font-medium text-stone-500 hover:text-stone-900 transition-colors duration-300 relative group">
                    {{ __('nav.about') }}
                    <span
                        class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-0 h-px bg-stone-900 transition-all duration-300 group-hover:w-full"></span>
                </a>
            </div>

            <div class="hidden md:flex items-center gap-6 rtl:space-x-reverse">

                <div class="flex items-center gap-2 text-xs font-medium text-stone-400">
                    <a href="{{ url('/lang?lang=en') }}"
                        class="{{ session('locale', config('app.locale')) == 'en' ? 'text-stone-900' : 'hover:text-stone-600' }} transition">
                        EN
                    </a>
                    <span class="text-stone-300">/</span>
                    <a href="{{ url('/lang?lang=ar') }}"
                        class="{{ session('locale', config('app.locale')) == 'ar' ? 'text-stone-900' : 'hover:text-stone-600' }} transition">
                        AR
                    </a>
                </div>

                <a href="/dashboard" class="text-stone-400 hover:text-stone-900 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </a>

                <a href="/contact"
                    class="px-6 py-3 bg-stone-900 text-[#FDFCF8] text-xs uppercase tracking-widest hover:bg-stone-700 transition-colors duration-300">
                    {{ __('nav.contact') }}
                </a>
            </div>

            <div class="md:hidden flex items-center gap-4">
                <a href="{{ url('/lang?lang=' . (session('locale') == 'en' ? 'ar' : 'en')) }}"
                    class="text-xs font-bold text-stone-900 uppercase">
                    {{ session('locale') == 'en' ? 'AR' : 'EN' }}
                </a>

                <button @click="open = !open" type="button"
                    class="text-stone-900 hover:text-stone-600 focus:outline-none">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 9h16.5m-16.5 6.75h16.5" />
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="md:hidden absolute top-full left-0 w-full bg-[#FDFCF8] border-b border-stone-200 shadow-xl">

        <div class="px-8 py-8 space-y-6 flex flex-col items-center text-center">
            <a href="/" class="text-lg font-serif text-stone-900 hover:text-stone-600 transition">
                {{ __('nav.home') }}
            </a>
            <a href="/projects" class="text-lg font-serif text-stone-900 hover:text-stone-600 transition">
                {{ __('nav.projects') }}
            </a>
            <a href="/services" class="text-lg font-serif text-stone-900 hover:text-stone-600 transition">
                {{ __('nav.services') }}
            </a>
            <a href="/about" class="text-lg font-serif text-stone-900 hover:text-stone-600 transition">
                {{ __('nav.about') }}
            </a>
            <a href="/dashboard"
                class="text-sm uppercase tracking-widest text-stone-400 hover:text-stone-900 transition pt-4">
                {{ __('nav.dashboard') }}
            </a>
            <a href="/contact"
                class="w-full max-w-xs py-4 bg-stone-900 text-[#FDFCF8] uppercase tracking-widest text-sm hover:bg-stone-700 transition">
                {{ __('nav.contact') }}
            </a>
        </div>
    </div>
</nav>

{{-- Spacer to prevent content overlap --}}
<div class="h-24 bg-[#FDFCF8]"></div>


<script>
    window.addEventListener("beforeunload", () => {
        localStorage.setItem("scrollPos", window.scrollY);
    });

    window.addEventListener("load", () => {
        const scrollPos = localStorage.getItem("scrollPos");
        if (scrollPos !== null) {
            window.scrollTo(0, scrollPos);
        }
    });
</script>