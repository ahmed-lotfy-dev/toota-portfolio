<aside class="w-64 bg-stone-900 text-stone-200 min-h-screen p-6 space-y-6">
    <div class="text-2xl font-bold tracking-wide text-white">
        Dashboard
    </div>

    <nav class="space-y-2 text-sm">
        <!-- Starter Kit Default Links -->
        <a href="{{ url('/dashboard') }}" class="block px-3 py-2 rounded-lg hover:bg-stone-800 transition">Home</a>

        <hr class="border-stone-700 my-4" />

        <!-- CRUD Sections -->
        <div class="uppercase text-xs text-stone-500 px-3">Content</div>
        <a href="{{ url('/dashboard/categories') }}"
            class="block px-3 py-2 rounded-lg hover:bg-stone-800 transition">Categories</a>
        <a href="{{ url('/dashboard/projects') }}"
            class="block px-3 py-2 rounded-lg hover:bg-stone-800 transition">Projects</a>

        <hr class="border-stone-700 my-4" />

        <!-- Profile Sections -->
        <div class="uppercase text-xs text-stone-500 px-3">Profile</div>
        <a href="{{ url('/dashboard/profile') }}"
            class="block px-3 py-2 rounded-lg hover:bg-stone-800 transition">Profile</a>
        <a href="{{ url('/dashboard/password') }}"
            class="block px-3 py-2 rounded-lg hover:bg-stone-800 transition">Password</a>
        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
            <a href="{{ url('/dashboard/two-factor') }}"
                class="block px-3 py-2 rounded-lg hover:bg-stone-800 transition">Two-Factor Auth</a>
        @endif
        <a href="{{ url('/dashboard/appearance') }}"
            class="block px-3 py-2 rounded-lg hover:bg-stone-800 transition">Appearance</a>
    </nav>
</aside>