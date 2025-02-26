@extends('document-layout', ['title' => 'Подборки ЖК'])

@section('pagescript')
    @vite('resources/js/agent/compilations.js')
@endsection

@section('content')
    @include('agent.compilation.header')
    @include('agent.compilation.gallery')
@endsection
