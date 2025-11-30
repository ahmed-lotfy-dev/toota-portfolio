<div class="w-full min-h-screen flex flex-col ">
  <x-landing.hero />
  <livewire:projects />
  <x-landing.about />
  <x-landing.services />
  <x-landing.process />
  <x-testimonials :testimonials="$testimonials" />
  <x-landing.faq />
  <livewire:contact-form />
</div>