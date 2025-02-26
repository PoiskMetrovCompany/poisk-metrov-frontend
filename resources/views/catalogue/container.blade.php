<div class="base-container" id="catalogue-container">
    @include('catalogue.catalogue-filters', ['searchData' => json_decode($filterData)])
    @include('catalogue.building-card-gallery', ['subtype' => 'vertical'])
</div>
