@extends('document-layout', ['title' => 'Новости'])

@section('content')
    @vite('resources/js/news/newsLoader.js')
    <h1 class="title technical">Новости</h1>
    <div id='news-conteiner' class="news-container">
        <div class="news-page" id='news-page'>
        </div>
    </div>
@endsection