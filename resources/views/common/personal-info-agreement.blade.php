<div class="peinag base-container">
    <input type="submit" value={!! $buttonText ?? 'Отправить' !!} class="peinag button {!! $type ?? '' !!}">
    @include('common.you-agree')
</div>
