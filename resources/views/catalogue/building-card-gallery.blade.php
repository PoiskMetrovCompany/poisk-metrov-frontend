<div id={{ $id ?? 'building-cards-grid' }} class="building-cards grid {{ $subtype ?? '' }}">
    @include('catalogue.nothing-found')
    @include('catalogue.found-count')
    <div class="catalogue-page" style="display: none"></div>
    @if (!empty($catalogueItems))
        @foreach ($catalogueItems as $catalogueItem)
            {!! $catalogueItem !!}
        @endforeach
    @endif
    <div class="paginator parent">
        {!! $paginator !!}
    </div>
</div>
