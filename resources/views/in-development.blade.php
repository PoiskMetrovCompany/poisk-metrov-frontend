@extends('document-layout', ['title' => 'Страница в разработке'])

@section('pagescript')
    @vite('resources/js/nonScrollPage/loadNonScrollPage.js')
    @vite('resources/js/nonScrollPage/makeFooterShorter.js')
@endsection

@section('content')
    <div class="developing container">
        <div class="developing left">
            <div class="developing title first">
                <span class="link-highlight">Страница находится<br>в разработке</span>
            </div>
            <a href="https://t.me/poisk_metrov" target="_blank" class="developing subscribe-to-telegram container">
                <div class="developing subscribe-to-telegram telegram-button">
                    <div class="icon telegram white"></div>
                </div>
                <div class="developing subscribe-to-telegram title">Подпишитесь на наш<br> Telegram-канал</div>
                <div class="developing subscribe-to-telegram description">и будьте в курсе событий и полезных новостей!
                </div>
            </a>
        </div>
        <div class="developing photo"></div>
    </div>
@endsection
