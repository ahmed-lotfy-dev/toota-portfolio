<link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;500&display=swap"
    rel="stylesheet">

<section
    class="relative w-full min-h-[90vh] flex items-center justify-center bg-[#FDFCF8] overflow-hidden selection:bg-stone-800 selection:text-white">

    <div class="absolute inset-0 pointer-events-none">
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-orange-100/40 rounded-full blur-[120px]">
        </div>

        <div class="absolute inset-0 opacity-[0.03]"
            style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noise%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noise)%22 opacity=%221%22/%3E%3C/svg%3E');">
        </div>
    </div>

    <div class="absolute inset-0 pointer-events-none z-0">

        <div
            class="absolute top-[-5%] left-[-5%] md:left-[5%] md:top-[10%] w-48 md:w-64 aspect-[3/4] bg-stone-200 shadow-2xl rotate-[-6deg] border-[8px] border-white transform transition duration-1000 hover:rotate-0 hover:scale-105 hover:z-20 opacity-30 md:opacity-100">
            <img src="https://images.unsplash.com/photo-1595418878648-2615a133df1f?q=80&w=800&auto=format&fit=crop"
                alt="Mask Detail" class="w-full h-full object-cover grayscale-[20%] contrast-110">
        </div>

        <div
            class="absolute bottom-[-5%] right-[-10%] md:right-[5%] md:bottom-[15%] w-56 md:w-80 aspect-video bg-stone-200 shadow-xl rotate-[3deg] border-[8px] border-white transform transition duration-1000 hover:rotate-0 hover:scale-105 hover:z-20 opacity-30 md:opacity-100">
            <img src="https://images.unsplash.com/photo-1576495149368-24eb224976c6?q=80&w=800&auto=format&fit=crop"
                alt="Workshop Tools" class="w-full h-full object-cover sepia-[0.2]">
        </div>

        <div
            class="absolute top-[5%] right-[-5%] md:right-[15%] md:top-[15%] w-32 md:w-48 aspect-square bg-stone-200 shadow-lg rotate-[12deg] border-[6px] border-white transform transition duration-1000 hover:rotate-0 hover:scale-105 hover:z-20 opacity-20 md:opacity-90">
            <img src="https://images.unsplash.com/photo-1542129202-b2d49c693427?q=80&w=800&auto=format&fit=crop"
                alt="Finished Prop" class="w-full h-full object-cover">
        </div>

        <div
            class="absolute bottom-[10%] left-[-8%] md:left-[12%] md:bottom-[10%] w-32 md:w-48 aspect-[2/3] bg-stone-200 shadow-xl rotate-[-12deg] border-[6px] border-white transform transition duration-1000 hover:rotate-0 hover:scale-105 hover:z-20 opacity-20 md:opacity-90">
            <img src="https://images.unsplash.com/photo-1596280687154-1563f68340d1?q=80&w=800&auto=format&fit=crop"
                alt="Artisan Hands" class="w-full h-full object-cover grayscale">
        </div>

        <div
            class="hidden lg:block absolute top-[40%] right-[20%] w-24 h-24 bg-stone-800 rounded-full blur-xl opacity-10">
        </div>
    </div>

    <div class="relative z-10 max-w-3xl mx-auto px-6 text-center">

        <span
            class="inline-block py-1 px-3 border border-stone-300 rounded-full text-xs font-medium tracking-widest text-stone-500 uppercase mb-6 bg-white/50 backdrop-blur-sm">
            {{ __('Handmade in the Studio') }}
        </span>

        <h1 class="text-5xl md:text-7xl lg:text-8xl text-stone-900 mb-8 leading-[0.9] tracking-tight"
            style="font-family: 'Playfair Display', serif;">
            {{ __('messages.hero.title') }}
            <span class="block text-2xl md:text-4xl italic text-stone-500 font-normal mt-4">
                & {{ __('Curated Oddities') }}
            </span>
        </h1>

        <p class="text-lg md:text-xl text-stone-600 mb-10 max-w-xl mx-auto leading-relaxed font-light"
            style="font-family: 'Inter', sans-serif;">
            Forging identities through clay, leather, and imagination.
            Detailed props and masks for the bold, the strange, and the storytellers.
        </p>

        <div class="flex flex-col md:flex-row gap-4 justify-center items-center">

            <a href="#gallery"
                class="group relative px-8 py-4 bg-stone-900 text-[#FDFCF8] text-sm tracking-widest uppercase transition-all duration-300 hover:bg-stone-800 hover:shadow-lg hover:-translate-y-1">
                <span>{{ __('Explore Collection') }}</span>
                <span
                    class="absolute bottom-2 left-1/2 -translate-x-1/2 w-0 h-[1px] bg-stone-400 transition-all duration-300 group-hover:w-1/2"></span>
            </a>

            <a href="#commissions"
                class="px-8 py-4 text-stone-600 hover:text-stone-900 text-sm tracking-widest uppercase transition-colors flex items-center gap-2 group">
                {{ __('Request Commission') }}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-4 h-4 transition-transform group-hover:translate-x-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                </svg>
            </a>
        </div>

    </div>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-50 animate-bounce">
        <span class="text-[10px] uppercase tracking-widest text-stone-400">Scroll</span>
        <div class="w-[1px] h-12 bg-stone-300"></div>
    </div>

</section>