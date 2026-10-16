@props(['name', 'label' => null, 'value' => null, 'rows' => 4, 'hint' => null, 'required' => false])
@php
$id = $attributes->get('id', $name);
$hasError = $errors->has($name);
@endphp
<div {{ $attributes->only('class') }}>
    @if ($label)
        <label for="{{ $id }}" class="form-label">{{ $label }}@if ($required)<span class="text-red-500"> *</span>@endif</label>
    @endif
    <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" @required($required)
              {{ $attributes->except(['class', 'id'])->merge(['class' => 'form-input'.($hasError ? ' border-red-400' : '')]) }}>{{ old($name, $value) }}</textarea>
    @if ($hint && ! $hasError)<p class="mt-1.5 text-xs text-ink-500">{{ $hint }}</p>@endif
    @error($name)<p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
</div>
