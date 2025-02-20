@isset($data[$num])
    <li>
        <div type="subheader">{{ $data[$num]['name'] }}</div>
        <div>от {{ $data[$num]['min-square'] }} м²</div>
        <div>от {{ $data[$num]['min-price'] }}</div>
    </li>
@else
    <li>
        <div type="subheader">&nbsp;</div>
        <div>&nbsp;</div>
        <div>&nbsp;</div>
    </li>
@endisset
