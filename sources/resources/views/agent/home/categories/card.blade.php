@php
    if (isset($category)) {
        $link = $realEstateService->getCatalogueLinkForCategory($category);
    } else {
        $link = '/';
    }
@endphp

<a href="{{ $link }}" type="card">
    <h6>{!! $header !!}</h6>
    @isset($icon)
        @include('icons.icon', ['iconClass' => $icon, 'iconColor' => 'orange'])
    @endisset
</a>
