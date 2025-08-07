@extends('document-layout', ['title' => 'Поиск по фильтрам'])

@section('pagescript')
    @vite('resources/js/agent/residential-complex-search.js')
@endsection

@section('content')
    @include('fullscreenmap.map', ['id' => 'catalogue-map'])
    @include('agent.search.header')
    @include('catalogue.mobile', ['buttonsOnly' => true])
    @include('agent.search.filters')
@endsection
