<div class="catalog home">
    @vite('resources/js/gallery/homePageCardsGallery.js')
    <div class="section-header">
        <div class="title first">Лучшие предложения</div>
        @include('common.arrow-buttons-container', ['id' => 'catalogue-grid-buttons'])
    </div>
    <div id="catalogue-grid" class="building-cards grid horizontal">
        @php
            if (!isset($catalogueItems)) {
                $catalogueItems = $cachingService->getCards(
                    $residentialComplexRepository->getBestOffers()->pluck('code')->toArray(),
                );
            }
        @endphp
        @foreach ($catalogueItems as $bestOffer)
            @include('custom-elements.building-card', $bestOffer)
        @endforeach
    </div>
    @include('buttons.link', [
        'subclass' => 'centered',
        'link' => '/catalogue',
        'buttonText' => 'Перейти в каталог',
    ])
</div>
