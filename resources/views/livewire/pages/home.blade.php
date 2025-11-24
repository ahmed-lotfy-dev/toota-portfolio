<x-layouts.landing :title="__('Home')">
  <div class="w-full min-h-screen flex flex-col ">
    <x-landing.header />
    <x-landing.hero />
    <livewire:projects />
    <x-landing.about />
    <x-landing.services />
    <livewire:contact-form />
    <x-landing.footer />
  </div>
</x-layouts.landing>