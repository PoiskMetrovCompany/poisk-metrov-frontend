@extends('document-layout')

@section('content')
<div class="errors base-container">
    <div class="errors text-container">
        <h1>{{$code}}</h1>
        <p>{{$reason}}</p>
    </div>
    @include(
    'buttons.link',
    [
        'link' => '/',
        'buttonText' => 'Перейти на главную'
    ]
)
</div>
@endsection