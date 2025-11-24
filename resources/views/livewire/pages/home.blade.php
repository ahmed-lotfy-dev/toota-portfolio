  <x-layouts.landing :title="__('Home')">
    <div class="w-full min-h-screen flex flex-col ">
      <x-header />
      <x-hero />
      <livewire:projects />
      <x-about />
      <x-services />
      <livewire:contact-form />
      <x-footer />
    </div>
  </x-layouts.landing>