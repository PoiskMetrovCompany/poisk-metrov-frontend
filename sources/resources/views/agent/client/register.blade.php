@extends('document-layout', ['title' => 'Добавить&nbsp;клиента'])

@section('pagescript')
    @vite('resources/js/agent/client-register.js')
@endsection

@section('content')
    <form id="client-register-form" class="agent form" autocomplete="off">
        @include('agent.client.register.header')
        @include('agent.client.register.info')
        @include('agent.client.register.desires')
        @include('agent.client.register.bottom-buttons')
    </form>
@endsection
