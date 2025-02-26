@auth
    @php
        if (!isset($value)) {
            $value = $user->phone;
        }
    @endphp
@endauth
@guest
    @php
        if (!isset($value)) {
            $value = '+7 (';
        }
    @endphp
@endguest
@include('custom-elements.parts.input.required-php')

<phone-input @isset($id) id="{{ $id }}" @endisset>
    <fieldset>
        @include('custom-elements.parts.input.legend', ['legend' => $phoneInputTitle ?? 'Ваш телефон'])
        <div class="input-container">
            {{-- id="phone" --}}
            <input type="tel" name="phone" placeholder="{!! $placeholder ?? '+7 (' !!}"
                @if ($required) required @endif value="{{ $value }}">
            @include('custom-elements.parts.input.side-icons')
        </div>
    </fieldset>
    @include('custom-elements.parts.input.error', ['errorMessage' => 'Проверьте корректность номера'])
</phone-input>
