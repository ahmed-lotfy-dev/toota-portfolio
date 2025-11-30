<aside class="w-64 bg-stone-900 text-stone-200 min-h-screen p-6 space-y-6">
    <div class="text-2xl font-bold tracking-wide text-white">
        Dashboard
    </div>

    <nav class="space-y-2 text-sm">
        <!-- Starter Kit Default Links -->
        <a href="{{ url('/') }}" class="inline-flex items-center px-3 py-2 rounded-lg hover:bg-stone-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-stone-400 group-hover:text-stone-300">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.135-.439 1.576 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            Home
        </a>
        <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-3 py-2 rounded-lg hover:bg-stone-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-stone-400 group-hover:text-stone-300">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.135-.439 1.576 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            Dashboard Home
        </a>

        <hr class="border-stone-700 my-4" />

        <!-- CRUD Sections -->
        <div class="uppercase text-xs text-stone-500 px-3">Content</div>
        <a href="{{ url('/dashboard/categories') }}" class="inline-flex items-center px-3 py-2 rounded-lg hover:bg-stone-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-stone-400 group-hover:text-stone-300">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 0a2.25 2.25 0 002.25 2.25h12c1.036 0 1.905-.675 2.176-1.609a1.5 1.5 0 00-2.083-2.083 2.25 2.25 0 00-1.609-2.176c-.934-.271-2.227.132-2.227 1.609a1.5 1.5 0 00-2.083-2.083 2.25 2.25 0 00-1.609-2.176c-.934-.271-2.227.132-2.227 1.609z" />
            </svg>
            Categories
        </a>
        <a href="{{ url('/dashboard/projects') }}" class="inline-flex items-center px-3 py-2 rounded-lg hover:bg-stone-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-stone-400 group-hover:text-stone-300">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg>
            Projects
        </a>
        <a href="{{ route('testimonials.index') }}" class="inline-flex items-center px-3 py-2 rounded-lg hover:bg-stone-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-stone-400 group-hover:text-stone-300">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.25.992 2.247 2.235 2.247H6.75v-.606c0-2.296 1.706-4.148 3.996-4.148h3.499c2.29 0 3.996 1.852 3.996 4.148v.606h1.515c1.243 0 2.235-.997 2.235-2.247V6.62c0-1.25-.992-2.247-2.235-2.247h-1.515a2.247 2.247 0 01-2.235-2.247V2.25h-1.515a2.247 2.247 0 01-2.235 2.247H6.75a2.247 2.247 0 01-2.235-2.247V2.25H3.75A1.5 1.5 0 002.25 3.75v12.01c0 1.25.992 2.247 2.235 2.247H6.75a2.247 2.247 0 01-2.235-2.247V16.5h1.515a2.247 2.247 0 012.235 2.247V21.75h1.515a2.247 2.247 0 012.235-2.247H17.25c1.243 0 2.235-.997 2.235-2.247V15h1.515a2.247 2.247 0 002.235-2.247V9.75h-1.515a2.247 2.247 0 01-2.235-2.247V6.75h-1.515a2.247 2.247 0 01-2.235 2.247H6.75V8.25H3.75zm0 0z" />
            </svg>
            Testimonials
        </a>

        <hr class="border-stone-700 my-4" />

        <!-- Profile Sections -->
        <div class="uppercase text-xs text-stone-500 px-3">Profile</div>
        <a href="{{ url('/dashboard/profile') }}" class="inline-flex items-center px-3 py-2 rounded-lg hover:bg-stone-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-stone-400 group-hover:text-stone-300">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0H4.5z" />
            </svg>
            Profile
        </a>
        <a href="{{ url('/dashboard/password') }}" class="inline-flex items-center px-3 py-2 rounded-lg hover:bg-stone-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-stone-400 group-hover:text-stone-300">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v3.75m-.75 1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v3.75M12 12V9m3 3v-3m-6 3v-3m3 6v-3m3 3v-3m-6 3v-3m3 6v-3m3 3v-3m-6 3v-3" />
            </svg>
            Password
        </a>
        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
            <a href="{{ url('/dashboard/two-factor') }}" class="inline-flex items-center px-3 py-2 rounded-lg hover:bg-stone-800 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-stone-400 group-hover:text-stone-300">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 10.5m-3.75 0a3.75 3.75 0 107.5 0 3.75 3.75 0 10-7.5 0zM17.25 6.75h1.25m-.75 3.5h1.25m-.75 3.5h1.25m-.75 3.5h1.25M17.25 2.25h1.25m-.75 3.5h1.25m-.75 3.5h1.25m-.75 3.5h1.25M17.25 20.25h1.25m-.75 3.5h1.25m-.75 3.5h1.25m-.75 3.5h1.25" />
                </svg>
                Two-Factor Auth
            </a>
        @endif
        <a href="{{ url('/dashboard/appearance') }}" class="inline-flex items-center px-3 py-2 rounded-lg hover:bg-stone-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-stone-400 group-hover:text-stone-300">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.716 1.133L3 21.75l1.98-1.98c.176.671.6 1.362 1.458 1.458A3 3 0 009.53 16.122zM15 6.75a3 3 0 00-3-3h-.75a3 3 0 00-3 3v.75a3 3 0 003 3H12a3 3 0 003-3V6.75zM12 11.25a3 3 0 013-3h.75a3 3 0 013 3v.75a3 3 0 01-3 3H15a3 3 0 01-3-3V11.25zM16.122 14.47a3 3 0 001.133-5.716L21.75 9l-1.98 1.98c.671.176 1.362.6 1.458 1.458A3 3 0 0016.122 14.47z" />
            </svg>
            Appearance
        </a>

        <hr class="border-stone-700 my-4" />

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left inline-flex items-center px-3 py-2 rounded-lg hover:bg-red-900/20 text-red-400 hover:text-red-300 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-red-400 group-hover:text-red-300">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3 3m0 0l-3 3m3-3H6.75" />
                </svg>
                Log Out
            </button>
        </form>
    </nav>
</aside>