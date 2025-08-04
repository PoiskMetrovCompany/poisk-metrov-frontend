@php
    $whatCity = $cityService->what[$selectedCity];
    $whereCity = $cityService->where[$selectedCity];
@endphp

@extends('document-layout', ['title' => "Новостройки $whatCity: Каталог новых квартир и жилых комплексов 2024 | Купить квартиру в новостройке недорого"])

@section('additional-meta')
    <meta name='description'
        content="Ищете новостройки в {{ $whereCity }}? Ознакомьтесь с каталогом актуальных предложений от Поиск-Метров: новые квартиры и жилые комплексы 2024 года. Подробные описания, цены, фото и планировки. Найдите квартиру своей мечты в {{ $whereCity }}!">
@endsection

@section('pagescript')
    <script>
        let filterData = {!! $filterData !!};
    </script>
    @vite('resources/js/catalogueFilters/filters.js')
@endsection

@section('content')
    @include('fullscreenmap.map', ['id' => 'catalogue-map'])
    @include('catalogue.mobile')
    @include('catalogue.container')
    @include('catalogue.get-free-catalogue')
    @include('common.we-choose')
    @include('menus.forms.learn-about-first-sale')
@endsection
