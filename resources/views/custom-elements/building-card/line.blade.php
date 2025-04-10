@isset($data[$num])
    <li>
        @php
            $link = '#';

            if ($data[$num]['name'] === 'студ.') $link= '#apartment_type_study';
            if ($data[$num]['name'] === '1к. кв.') $link= '#apartment_type_one';
            if ($data[$num]['name'] === '2к. кв.') $link= '#apartment_type_two';
            if ($data[$num]['name'] === '3к. кв.') $link= '#apartment_type_free';
            if ($data[$num]['name'] === '4к. кв.') $link= '#apartment_type_foo';
            if ($data[$num]['name'] === '5к. кв.') $link= '#apartment_type_five';
            if ($data[$num]['name'] === '6к. кв.') $link= '#apartment_type_six';
        @endphp
        <a class="apartment-type-card-item" href="/{{$code}}/{{$link}}" style="
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(0, 1fr));
            justify-content: space-around;
            align-items: center;
            width: 21rem;
            gap: 10px;
        ">
            <div type="subheader">{{ $data[$num]['name'] }}</div>
            <div>от {{ $data[$num]['min-square'] }} м²</div>
            <div>от {{ $data[$num]['min-price'] }}</div>
        </a>
    </li>
@else
    <li>
        <div type="subheader">&nbsp;</div>
        <div>&nbsp;</div>
        <div>&nbsp;</div>
    </li>
@endisset
