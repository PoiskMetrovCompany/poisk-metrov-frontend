<div id="{{ $id }}" tabindex="-1" class="favorites dropdown container">
    <div class="icon sorting d20x20 orange"> </div>
    <span class="search-catalogue dropdown placeholder">{{ $items[0] }}</span>
    <div class="icon arrow-tailless grey5"></div>
    <div class="custom-dropdown base-container">
        @foreach ($items as $item)
            <div class="names-dropdown item">
                <div>
                    <span>{{ $item }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>
