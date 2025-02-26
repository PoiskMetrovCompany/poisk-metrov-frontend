@isset($defaultOption)
    <span class="placeholder chosen">
        {{ $defaultOption }}
    </span>
@else
    <span class="placeholder">
        @isset($placeholder)
            {{ $placeholder }}
        @endisset
    </span>
@endisset
