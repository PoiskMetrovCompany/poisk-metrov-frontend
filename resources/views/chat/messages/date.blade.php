<div class="chat-window date-separator">
    @if (!isset($date))
        @php
            $date = \Illuminate\Support\Carbon::today();
        @endphp
    @endif
    {{ $date }}
</div>
