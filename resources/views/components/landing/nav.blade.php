<nav class="bg-white shadow-lg" x-data="{ open: false }">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="flex justify-between items-center h-16">
			<!-- Logo -->
			<div class="flex-shrink-0">
				<a href="/" class="text-2xl font-bold text-gray-800">
					{{ __('messages.nav.brand') }}
				</a>
			</div>

			<!-- Desktop Menu -->
			<div class="hidden md:flex space-x-8 rtl:space-x-reverse">
				<a href="/" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition">
					{{ __('messages.nav.home') }}
				</a>
				<a href="/services"
					class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition">
					{{ __('messages.nav.projects') }}
				</a>
				<a href="/services"
					class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition">
					{{ __('messages.nav.services') }}
				</a>
				<a href="/about" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition">
					{{ __('messages.nav.about') }}
				</a>
				<a href="/contact"
					class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition">
					{{ __('messages.nav.contact') }}
				</a>

				<a href="/dashboard"
					class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition">
					{{ __('messages.nav.dashboard') }}
				</a>
			</div>
			<!-- Language Switcher -->
			<div class="hidden md:flex items-center space-x-4 rtl:space-x-reverse">
				@if (session('locale', config('app.locale')) != 'en')
					<a href="{{ url('/lang?lang=en') }}"
						class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition">
						En
					</a>
				@endif
				@if (session('locale', config('app.locale')) != 'ar')
					<a href="{{ url('/lang?lang=ar') }}"
						class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition">
						Ar
					</a>
				@endif
			</div>

			<!-- Mobile Menu Button -->
			<div class="md:hidden">
				<button @click="open = !open" type="button"
					class="text-gray-700 hover:text-blue-600 focus:outline-none focus:text-blue-600">
					<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M4 6h16M4 12h16M4 18h16" />
						<path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M6 18L18 6M6 6l12 12" />
					</svg>
				</button>
			</div>
		</div>
	</div>

	<!-- Mobile Menu -->
	<div x-show="open" x-transition:enter="transition ease-out duration-200"
		x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100"
		x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100"
		x-transition:leave-end="opacity-0 transform scale-95" class="md:hidden">
		<div class="px-2 pt-2 pb-3 space-y-1">
			<a href="/"
				class="text-gray-700 hover:bg-blue-50 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition">
				{{ __('messages.nav.home') }}
			</a>
			<a href="/projects"
				class="text-gray-700 hover:bg-blue-50 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition">
				{{ __('messages.nav.projects') }}
			</a>
			<a href="/services"
				class="text-gray-700 hover:bg-blue-50 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition">
				{{ __('messages.nav.about') }}
			</a>
			<a href="/about"
				class="text-gray-700 hover:bg-blue-50 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition">
				{{ __('messages.nav.about') }}
			</a>
			<a href="/contact"
				class="text-gray-700 hover:bg-blue-50 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition">
				{{ __('messages.nav.contact') }}
			</a>
			<a href="/dashboard"
				class="text-gray-700 hover:bg-blue-50 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition">
				{{ __('messages.nav.dashboard') }}
			</a>
			<!-- Language Switcher -->
			<div class="flex items-center space-x-4 rtl:space-x-reverse px-3 py-2">
				@if (session('locale', config('app.locale')) != 'en')
					<a href="{{ url('/lang?lang=en') }}"
						class="text-gray-700 hover:text-blue-600 rounded-md text-sm font-medium transition">
						En
					</a>
				@endif
				@if (session('locale', config('app.locale')) != 'ar')
					<a href="{{ url('/lang?lang=ar') }}"
						class="text-gray-700 hover:text-blue-600 rounded-md text-sm font-medium transition">
						Ar
					</a>
				@endif
			</div>
		</div>
	</div>
</nav>