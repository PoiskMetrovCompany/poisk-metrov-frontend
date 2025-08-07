@php
    $data = $options->data;
    $realPreview = $title;

    if (isset($preview)) {
        $realPreview = $preview;
    }

    foreach ($data as $key => $value) {
        $firstValue = $value;
        break;
    }
@endphp
<span class="search-catalogue dropdown placeholder">{{ $realPreview }}</span>
