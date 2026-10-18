{{-- Champ de formulaire avec label, aide et message d'erreur. --}}
@props(['name', 'label' => null, 'type' => 'text', 'value' => null, 'hint' => null, 'required' => false])
@php
$id = $attributes->get('id', str_replace(['[', ']', '.'], '_', $name));
$errorKey = str_replace(['[', ']'], ['.', ''], $name);
$hasError = $errors->has($errorKey);
@endphp
<div {{ $attributes->only('class') }}>
    @if ($label)
        <label for="{{ $id }}" class="form-label">{{ $label }}@if ($required)<span class="text-red-500"> *</span>@endif</label>
    @endif
    <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}"
           @if ($type !== 'password' && $type !== 'file') value="{{ old($errorKey, $value) }}" @endif
           @required($required)
           @if ($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
           {{ $attributes->except(['class', 'id'])->merge(['class' => 'form-input'.($hasError ? ' border-red-400 focus:border-red-500 focus:ring-red-500/20' : '')]) }}>
    @if ($hint && ! $hasError)<p class="mt-1.5 text-xs text-ink-500">{{ $hint }}</p>@endif
    @error($errorKey)<p id="{{ $id }}-error" class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
</div>
