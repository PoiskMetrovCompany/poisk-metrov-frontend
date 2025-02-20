@php
    if (!isset($news)) {
        $news = $newsService->getNewsForSite();
    }
@endphp

@if (count($news) > 0)
    <div class="news base-container">
        <div class="title-flex">
            <div class="title">Новости</div>
        </div>
        <div class="news container">
            @include('home-page.news.card.primary', $news[0])
            @if (count($news) > 1)
                @include('home-page.news.card.secondary', $news[1])
            @endif
            @if (count($news) > 2)
                @include('home-page.news.card.secondary', $news[2])
            @endif
        </div>
        @include('buttons.link', [
            'subclass' => 'centered',
            'link' => '/news-page',
            'buttonText' => 'Читать все',
        ])
    </div>
@endif
