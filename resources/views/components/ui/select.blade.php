@props(['label', 'name', 'options' => [], 'placeholder' => 'Select an option'])

<div>
  @if($label)
    <label for="{{ $name }}" class="block mb-1">{{ $label }}</label>
  @endif
  <select id="{{ $name }}" {{ $attributes->merge(['class' => 'w-full p-2 rounded border dark:bg-gray-700 dark:text-white ' . ($errors->has($name) ? 'border-red-500' : '')]) }}>
    @if($placeholder)
      <option value="">{{ $placeholder }}</option>
    @endif

    {{ $slot }}

    @foreach($options as $value => $label)
      <option value="{{ $value }}">{{ $label }}</option>
    @endforeach
  </select>

  @error($name)
    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
  @enderror
</div>