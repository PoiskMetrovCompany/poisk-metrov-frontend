@foreach($fields as $items)
    <div class="inputs">
        @if (gettype($items) === 'array' && count($items) > 1)
            @foreach($items as $item => $key)
                @if (!empty($key) && is_array($key))
                    @php
                        $attributes = [
                            'nameInputTitle' => $key['name'],
                            'value' => ''
                        ];

                        if (key_exists('placeholder', $key)) {
                            $attributes['placeholder'] = $key['placeholder'];
                        }
                        if ($key['inputType'] === 'selection') {
                            $attributes['values'] = $key['values'];
                        }
                        if (
                            key_exists('inputName', $key)
                            && key_exists('inputType', $key)
                            && key_exists('inputId', $key)
                        ) {
                            $attributes['inputName'] = $key['inputName'];
                            $attributes['inputType'] = $key['inputType'];
                            $attributes['inputId'] = $key['inputId'];

                            $attributes['inputIcon'] = key_exists('inputIcon', $key) ? $key['inputIcon'] : '';
                            $attributes['name'] = $key['name'];

//                            $attributes['classList'] = $attributes['inputName'] === 'work_sub_employment_contract' ? 'row-70' : '';
//                            $attributes['classList'] = $attributes['inputName'] === 'work_sub_employment_contract' ? 'row-70' : '';
                        }
                    @endphp
                    @include($key['field'], $attributes)
                @endif
            @endforeach
        @elseif (gettype($items) === 'array' && count($items) === 1)
            @php
                $attributes = [
                    'nameInputTitle' => $items[0]['name'],
                    'value' => ''
                ];

                if (key_exists('placeholder', $items[0])) {
                    $attributes['placeholder'] = $items[0]['placeholder'];
                }
                if ($items[0]['inputType'] === 'selection') {
                    $attributes['values'] = $items[0]['values'];
                } elseif (
                    $items[0]['inputType'] != 'selection'
                    && key_exists('inputName', $items[0])
                    && key_exists('inputType', $items[0])
                    && key_exists('inputId', $items[0])
                ) {
                    $attributes['inputType'] = $items[0]['inputType'];
                    $attributes['inputIcon'] = key_exists('inputIcon', $items[0]) ? $items[0]['inputIcon'] : '';
                    $attributes['name'] = $items[0]['name'];

//                    $attributes['classList'] = $attributes['inputName'] === 'work_sub_employment_contract' ? 'row-70' : '';
//                    $attributes['classList'] = $attributes['inputName'] === 'work_sub_employment_contract' ? 'row-70' : '';
                }
                $attributes['inputName'] = $items[0]['inputName'];
                $attributes['inputId'] = $items[0]['inputId'];
            @endphp
            @include($items[0]['field'], $attributes)
        @endif
    </div>
@endforeach

