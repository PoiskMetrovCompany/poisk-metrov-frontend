<div class="tab{{ $enabled ? ' enabled' : '' }}" data-name="{{ $dataName }}">
    <div class="document-download with-icon">
        {{ $tabText }}
        @isset($iconClass)
            <div class="icon {{ $iconClass }} d16x16 disabled"></div>
        @endisset
    </div>
</div>
