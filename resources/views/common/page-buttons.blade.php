<div id="{{ $id }}" class="paginator base-container"
    @if ($pageCount == 1) style="display: none" @endif>
    <div class="paginator text-button disabled">Предыдущий</div>
    <div class="paginator buttons-grid">
        @isset($pageCount)
            <div class="paginator page-button current">1</div>
            @for ($i = 1; $i < $pageCount - 1; $i++)
                <div class="paginator page-button">{{ $i + 1 }}</div>
            @endfor
        @endisset
    </div>
    <div class="paginator text-button">Следующий</div>
</div>
