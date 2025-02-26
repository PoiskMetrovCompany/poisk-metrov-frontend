<fieldset class="input-fieldset">
    <legend class="input-legend disabled">{{ $phoneInputTitle ?? "Ваш телефон" }}</legend>
    <input class="input-text {{$style ?? ''}}" name="phone" type="text" required disabled placeholder="{{$value}}">
</fieldset>