<div id='paginator-with-show-more' class='paginator base-container full-width'>
    <div id="{{ $id }}" class="paginator base-container"
        @if ($pageCount == 1) style="display: none" @endif>
        <button class="paginator text-button">@include('icons.arrow-left')</button>
        <div class="paginator buttons-grid">
            @isset($pageCount)
                <div class="paginator page-button current">
                    <a href="page=1" onclick="return false;" class="paginator link">1</a>
                </div>
                @for ($i = 1; $i < $pageCount - 1; $i++)
                    <div class="paginator page-button">
                        <a href="page={{ $i + 1 }}" onclick="return false;" class="paginator link">
                            {{ $i + 1 }}
                        </a>
                    </div>
                @endfor
            @endisset
        </div>
        <button class="paginator text-button">@include('icons.arrow-right')</button>
    </div>
    @include('common.loader-dots', ['id' => 'building-loader-dots'])
    <button class="paginator text-button" id='show-all-paginator'>Показать все</button>
</div>
