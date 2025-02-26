@php
    if (!isset($allowMultiple)) {
        $allowMultiple = false;
    }

    if (!isset($allowDeselect)) {
        $allowDeselect = true;
    }

    if (!isset($selectedButton)) {
        $selectedButton = '';
    }
@endphp

<buttons-grid @isset($id) id="{{ $id }}" @endisset allowMultiple="{{ $allowMultiple }}"
    allowDeselect="{{ $allowDeselect }}">
    @isset($buttons)
        @foreach ($buttons as $button)
            <button type="button" selected="{{ $selectedButton == $button ? 'true' : 'false' }}">{{ $button }}</button>
        @endforeach
    @endisset
</buttons-grid>
