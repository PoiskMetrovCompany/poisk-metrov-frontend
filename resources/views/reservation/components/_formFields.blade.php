@foreach($fields as $items)
    <div class="inputs">
        @if (gettype($items) === 'array' && count($items) > 1)
            @foreach($items as $item => $key)
                @if (is_array($key))
                    @php
                        $attributes = [
                            'nameInputTitle' => $key['name'],
                            'value' => ''
                        ];

                        if (key_exists('placeholder', $key)) {
                            $attributes['placeholder'] = $key['placeholder'];
                        }
                        if ($key['type'] === 'selection') {
                            $attributes['values'] = $key['values'];
                        }
                    @endphp
                    @include($key['field'], $attributes)
                @endif
            @endforeach
        @else
        @endif
    </div>
@endforeach

{{--    @include('inputs.name', ['required' => "*"])--}}
{{--    @include('inputs.last-name', ['required' => "*"])--}}
{{--    @include('inputs.middle-name', ['required' => "*"])--}}
{{--    @include('inputs.phone', ['required' => "*"])--}}

