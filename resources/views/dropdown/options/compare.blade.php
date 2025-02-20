@php
    $data = json_decode(json_encode($options->data), true);
    $startOptions = array_slice($data, 0, count($data));
    array_pop($startOptions);
    $endOptions = array_slice($data, 1, count($data));

    foreach ($startOptions as &$startOptionInLoop) {
        $startOptionInLoop['searchid'] = $options->searchidFrom;
        $startOptionInLoop['condition'] = '>=';
    }

    //https://www.php.net/manual/en/control-structures.foreach.php
    //Обязательно ансетим переменную чтобы не переписался последний элемент массива на предыдущий ему
    //Языку подчищать за собой - это не по царски
    unset($startOptionInLoop);

    foreach ($endOptions as &$endOptionInLoop) {
        $endOptionInLoop['searchid'] = $options->searchidTo;
        $endOptionInLoop['condition'] = '<=';
    }

    unset($endOptionInLoop);
@endphp

<div class="custom-dropdown base-container" allowMultiple={{ $options->allowMultiple }}>
    <div class="price-dropdown base-container">
        <input class="price-dropdown title" placeholder="{{ $options->inputPlaceholderFrom }}">
        <input class="price-dropdown title" placeholder="{{ $options->inputPlaceholderTo }}">
        <div class="price-dropdown container">
            @foreach ($startOptions as $startOption)
                @include('dropdown.elements.text-item', ['option' => $startOption])
            @endforeach
        </div>
        <div class="price-dropdown container">
            @foreach ($endOptions as $endOption)
                @include('dropdown.elements.text-item', ['option' => $endOption])
            @endforeach
        </div>
    </div>
</div>
