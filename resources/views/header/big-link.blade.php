<a href="/{{ $link }}" current="{{ Request::is($link) || Request::is("*/$link") }}">
    {{ $text }}
</a>
