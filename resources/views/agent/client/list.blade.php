@extends('document-layout', ['title' => 'Клиенты'])

@section('pagescript')
    @vite('resources/js/agent/client-list.js')
@endsection

@section('content')
    <div class="agent form">
        @include('agent.client.list.header')
        @include('agent.client.list.filters')
        @include('agent.client.list.clients')
    </div>
@endsection
