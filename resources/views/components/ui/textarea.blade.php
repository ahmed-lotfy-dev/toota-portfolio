@props(['label', 'name', 'rows' => 3, 'placeholder' => ''])

<div>
  @if($label)
    <label for="{{ $name }}" class="block mb-1">{{ $label }}</label>
  @endif
  <textarea id="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}" {{ $attributes->merge(['class' => 'w-full p-2 rounded border dark:bg-gray-700 dark:text-white ' . ($errors->has($name) ? 'border-red-500' : '')]) }}></textarea>

  @error($name)
    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
  @enderror
</div>