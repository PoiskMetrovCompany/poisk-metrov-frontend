<like-button type="button" for="{{ $for ?? 'building' }}" like="{{ $isFavorite }}" code="{{ $code }}">
    @include('icons.icon', ['iconClass' => 'action-like', 'iconColor' => 'black'])
    @include('common.hint', ['text' => 'Добавить в избранное'])
</like-button>
