@extends('document-layout', ['title' => 'Новости'])

@section('content')
    @vite('resources/js/gallery/newsGallery.js')
    <h1 class="title">Новости</h1>
    @include('article.banner', [
        'date' => $article['created_at'],
        'title' => $article['title'],
        'banner' => $article['title_image_file_name'],
        'photoAuthor' => '',
    ])
    <div class="article content">{!! $article['content'] !!}</div>
    <div class="article buttons">
        @include('common.arrow-buttons-container', ['id' => 'news-buttons', 'spaced' => 'spaced'])
    </div>
    <div class="article recomended" id="news-cards">
        @foreach ($recomended as $news)
            @include('common.news-card', [
                'banner' => "/news/{$news['title_image_file_name']}",
                'date' => $news['created_at'],
                'title' => $news['title'],
                'content' => $news['content'],
                'id' => $news['id'],
                'fixed' => 'fixed',
            ])
        @endforeach
    </div>
@endsection
