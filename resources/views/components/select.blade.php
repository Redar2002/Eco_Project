{{-- Liste déroulante. « options » = [valeur => libellé]. --}}
@props(['name', 'label' => null, 'options' => [], 'value' => null, 'placeholder' => null, 'required' => false])
@php
$id = $attributes->get('id', $name);
$selected = (string) old($name, $value);
@endphp
<div {{ $attributes->only('class') }}>
    @if ($label)
        <label for="{{ $id }}" class="form-label">{{ $label }}@if ($required)<span class="text-red-500"> *</span>@endif</label>
    @endif
    <select id="{{ $id }}" name="{{ $name }}" @required($required)
            {{ $attributes->except(['class', 'id'])->merge(['class' => 'form-input pr-10'.($errors->has($name) ? ' border-red-400' : '')]) }}>
        @if ($placeholder)<option value="">{{ $placeholder }}</option>@endif
        @foreach ($options as $optValue => $optLabel)
            <option value="{{ $optValue }}" @selected($selected === (string) $optValue)>{{ $optLabel }}</option>
        @endforeach
    </select>
    @error($name)<p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
</div>
