<div x-data="{ sidebarOpen: false }" x-cloak class="flex h-screen overflow-hidden bg-stone-900">
    <!-- Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 z-50 bg-stone-900 border-r border-stone-800 text-stone-200 transition-all duration-300 ease-in-out md:static shrink-0 overflow-y-auto max-h-screen [&::-webkit-scrollbar]:hidden [scrollbar-width:none]"
        :class="sidebarOpen ? 'w-64' : 'w-20 md:w-52'">
        <!-- Header -->
        <div class="flex items-center h-16 px-4 md:px-6 border-b border-stone-800 shrink-0"
            :class="sidebarOpen ? 'justify-between' : 'justify-center md:justify-between'">
            <!-- Dashboard Text -->
            <a href="{{ url('/') }}"
                class="text-2xl font-bold tracking-wide text-white hover:opacity-80 transition-opacity whitespace-nowrap overflow-hidden"
                :class="sidebarOpen ? 'block' : 'hidden md:block'">
                Dashboard
            </a>

            <!-- Mobile Menu Toggle (Mobile Only) -->
            <button @click="sidebarOpen = !sidebarOpen"
                class="md:hidden flex items-center justify-center w-10 h-10 text-sm font-medium rounded-md text-stone-400 hover:bg-stone-800 hover:text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>

        <!-- Nav Links -->
        <nav class="flex-1 px-4 py-4 space-y-1">
            <a href="{{ url('/') }}"
                class="flex items-center px-2 py-2 text-sm font-medium rounded-md group hover:bg-stone-800 hover:text-white {{ request()->is('/') ? 'bg-stone-800 text-white' : 'text-stone-400' }}"
                :class="sidebarOpen ? 'justify-start' : 'justify-center md:justify-start'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 shrink-0" :class="sidebarOpen ? 'mr-3' : 'mr-0 md:mr-3'">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 12l8.954-8.955c.44-.439 1.135-.439 1.576 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span :class="sidebarOpen ? 'block' : 'hidden md:block'">Home</span>
            </a>

            <a href="{{ url('/dashboard') }}"
                class="flex items-center px-2 py-2 text-sm font-medium rounded-md group hover:bg-stone-800 hover:text-white {{ request()->is('dashboard') ? 'bg-stone-800 text-white' : 'text-stone-400' }}"
                :class="sidebarOpen ? 'justify-start' : 'justify-center md:justify-start'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 shrink-0" :class="sidebarOpen ? 'mr-3' : 'mr-0 md:mr-3'">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>
                <span :class="sidebarOpen ? 'block' : 'hidden md:block'">Dashboard Home</span>
            </a>

            <div class="my-4 border-t border-stone-700"></div>

            <div class="px-2 mb-2 text-xs font-medium uppercase text-stone-500"
                :class="sidebarOpen ? 'block' : 'hidden md:block'">
                Content
            </div>

            <a href="{{ url('/dashboard/categories') }}"
                class="flex items-center px-2 py-2 text-sm font-medium rounded-md group hover:bg-stone-800 hover:text-white {{ request()->is('dashboard/categories*') ? 'bg-stone-800 text-white' : 'text-stone-400' }}"
                :class="sidebarOpen ? 'justify-start' : 'justify-center md:justify-start'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 shrink-0" :class="sidebarOpen ? 'mr-3' : 'mr-0 md:mr-3'">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                </svg>
                <span :class="sidebarOpen ? 'block' : 'hidden md:block'">Categories</span>
            </a>

            <a href="{{ url('/dashboard/projects') }}"
                class="flex items-center px-2 py-2 text-sm font-medium rounded-md group hover:bg-stone-800 hover:text-white {{ request()->is('dashboard/projects*') ? 'bg-stone-800 text-white' : 'text-stone-400' }}"
                :class="sidebarOpen ? 'justify-start' : 'justify-center md:justify-start'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 shrink-0" :class="sidebarOpen ? 'mr-3' : 'mr-0 md:mr-3'">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                </svg>
                <span :class="sidebarOpen ? 'block' : 'hidden md:block'">Projects</span>
            </a>

            <a href="{{ route('testimonials.index') }}"
                class="flex items-center px-2 py-2 text-sm font-medium rounded-md group hover:bg-stone-800 hover:text-white {{ request()->routeIs('testimonials.*') ? 'bg-stone-800 text-white' : 'text-stone-400' }}"
                :class="sidebarOpen ? 'justify-start' : 'justify-center md:justify-start'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 shrink-0" :class="sidebarOpen ? 'mr-3' : 'mr-0 md:mr-3'">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.25.992 2.247 2.235 2.247H6.75v-.606c0-2.296 1.706-4.148 3.996-4.148h3.499c2.29 0 3.996 1.852 3.996 4.148v.606h1.515c1.243 0 2.235-.997 2.235-2.247V6.62c0-1.25-.992-2.247-2.235-2.247h-1.515a2.247 2.247 0 01-2.235-2.247V2.25h-1.515a2.247 2.247 0 01-2.235 2.247H6.75a2.247 2.247 0 01-2.235-2.247V2.25H3.75A1.5 1.5 0 002.25 3.75v12.01c0 1.25.992 2.247 2.235 2.247H6.75a2.247 2.247 0 01-2.235-2.247V16.5h1.515a2.247 2.247 0 012.235 2.247V21.75h1.515a2.247 2.247 0 012.235-2.247H17.25c1.243 0 2.235-.997 2.235-2.247V15h1.515a2.247 2.247 0 002.235-2.247V9.75h-1.515a2.247 2.247 0 01-2.235-2.247V6.75h-1.515a2.247 2.247 0 01-2.235 2.247H6.75V8.25H3.75zm0 0z" />
                </svg>
                <span :class="sidebarOpen ? 'block' : 'hidden md:block'">Testimonials</span>
            </a>

            <div class="my-4 border-t border-stone-700"></div>

            <div class="px-2 mb-2 text-xs font-medium uppercase text-stone-500"
                :class="sidebarOpen ? 'block' : 'hidden md:block'">
                Profile
            </div>

            <a href="{{ url('/dashboard/profile') }}"
                class="flex items-center px-2 py-2 text-sm font-medium rounded-md group hover:bg-stone-800 hover:text-white {{ request()->is('dashboard/profile') ? 'bg-stone-800 text-white' : 'text-stone-400' }}"
                :class="sidebarOpen ? 'justify-start' : 'justify-center md:justify-start'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 shrink-0" :class="sidebarOpen ? 'mr-3' : 'mr-0 md:mr-3'">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0H4.5z" />
                </svg>
                <span :class="sidebarOpen ? 'block' : 'hidden md:block'">Profile</span>
            </a>

            <a href="{{ url('/dashboard/password') }}"
                class="flex items-center px-2 py-2 text-sm font-medium rounded-md group hover:bg-stone-800 hover:text-white {{ request()->is('dashboard/password') ? 'bg-stone-800 text-white' : 'text-stone-400' }}"
                :class="sidebarOpen ? 'justify-start' : 'justify-center md:justify-start'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 shrink-0" :class="sidebarOpen ? 'mr-3' : 'mr-0 md:mr-3'">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                </svg>
                <span :class="sidebarOpen ? 'block' : 'hidden md:block'">Password</span>
            </a>

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <a href="{{ url('/dashboard/two-factor') }}"
                    class="flex items-center px-2 py-2 text-sm font-medium rounded-md group hover:bg-stone-800 hover:text-white {{ request()->is('dashboard/two-factor') ? 'bg-stone-800 text-white' : 'text-stone-400' }}"
                    :class="sidebarOpen ? 'justify-start' : 'justify-center md:justify-start'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 shrink-0" :class="sidebarOpen ? 'mr-3' : 'mr-0 md:mr-3'">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                    </svg>
                    <span :class="sidebarOpen ? 'block' : 'hidden md:block'">Two-Factor Auth</span>
                </a>
            @endif

            <a href="{{ url('/dashboard/appearance') }}"
                class="flex items-center px-2 py-2 text-sm font-medium rounded-md group hover:bg-stone-800 hover:text-white {{ request()->is('dashboard/appearance') ? 'bg-stone-800 text-white' : 'text-stone-400' }}"
                :class="sidebarOpen ? 'justify-start' : 'justify-center md:justify-start'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 shrink-0" :class="sidebarOpen ? 'mr-3' : 'mr-0 md:mr-3'">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.53 16.122a3 3 0 00-5.716 1.133L3 21.75l1.98-1.98c.176.671.6 1.362 1.458 1.458A3 3 0 009.53 16.122zM15 6.75a3 3 0 00-3-3h-.75a3 3 0 00-3 3v.75a3 3 0 003 3H12a3 3 0 003-3V6.75zM12 11.25a3 3 0 013-3h.75a3 3 0 013 3v.75a3 3 0 01-3 3H15a3 3 0 01-3-3V11.25zM16.122 14.47a3 3 0 001.133-5.716L21.75 9l-1.98 1.98c.671.176 1.362.6 1.458 1.458A3 3 0 0016.122 14.47z" />
                </svg>
                <span :class="sidebarOpen ? 'block' : 'hidden md:block'">Appearance</span>
            </a>

            <a href="{{ url('/dashboard/backups') }}"
                class="flex items-center px-2 py-2 text-sm font-medium rounded-md group hover:bg-stone-800 hover:text-white {{ request()->is('dashboard/backups') ? 'bg-stone-800 text-white' : 'text-stone-400' }}"
                :class="sidebarOpen ? 'justify-start' : 'justify-center md:justify-start'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 shrink-0" :class="sidebarOpen ? 'mr-3' : 'mr-0 md:mr-3'">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                </svg>
                <span :class="sidebarOpen ? 'block' : 'hidden md:block'">Backups & Exports</span>
            </a>

            <div class="my-4 border-t border-stone-700"></div>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit"
                    class="w-full flex items-center px-2 py-2 text-sm font-medium rounded-md group text-red-400 hover:bg-red-900/20 hover:text-red-300"
                    :class="sidebarOpen ? 'justify-start' : 'justify-center md:justify-start'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 shrink-0" :class="sidebarOpen ? 'mr-3' : 'mr-0 md:mr-3'">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3 3m0 0l-3 3m3-3H6.75" />
                    </svg>
                    <span :class="sidebarOpen ? 'block' : 'hidden md:block'">Log Out</span>
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden ml-20 md:ml-0">
        <main class="flex-1 overflow-y-auto bg-[#FDFCF8]">
            {{ $slot }}
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-gray-600/50 md:hidden"></div>
</div>