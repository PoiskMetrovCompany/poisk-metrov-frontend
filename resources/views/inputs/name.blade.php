<div class="input-container">
    <fieldset class="input-fieldset">
        <legend class="input-legend">{{ $nameInputTitle ?? 'Ваше имя' }}<span
                class="red-highlight">{{ $required ?? '' }}</span></legend>
        <div class="input-wrapper">
            <input class="input-text {{ $style ?? '' }}" type="text" id="name" name="name" required
                placeholder={!! $placeholder ?? 'Введите&nbsp;ваше&nbsp;имя' !!}
                @if (isset($user) && !isset($nameInputTitle)) value="{{ $value ?? $user->name }}"
                @else value="{{ $value ?? '' }}" @endif>
            <img src="{{ Vite::asset('resources/assets/content/content-error.svg') }}" class="error-icon">
            <div class="icon action-close d20x20 grey3"></div>
        </div>
    </fieldset>
    <div class="input-error" id="input-error">Заполните поле корректно</div>
</div>
{{-- @php
    if (!isset($value)) {
        $value = '';

        if (isset($user) && !isset($legend)) {
        }
    }
@endphp
@include('custom-elements.text-input', [
    'type' => 'name',
]) --}}
