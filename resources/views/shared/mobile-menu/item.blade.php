<a href="/{{ $link }}"
    @if (Request::is($link) || Request::is("*/$link")) class="mobile-menu item  current"
    @else class="mobile-menu item" @endif>
    <div class="mobile-menu title">
        {{ $text }}
    </div>
    @include('common.divider')
</a>
