@php
    if (!isset($required)) {
        $required = false;
    }

    $today = \Carbon\Carbon::today()->format('d.m.Y');
@endphp

<date-picker @isset($id) id="{{ $id }}" @endisset>
    <fieldset>
        <div class="input-container">
            <input type="button" value="{{ $today }}">
            <div class="calendar-container">
                <div></div>
            </div>
            <div class="icons-container">
                @include('icons.calendar')
            </div>
        </div>
    </fieldset>
    @include('custom-elements.parts.input.error')
</date-picker>
