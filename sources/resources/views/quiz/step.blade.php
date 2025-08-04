<div @if (isset($isVisible) && $isVisible == true) class="quiz step visible" @else class="quiz step" @endif>
    @include('quiz.title')
    @isset($buttonTexts)
        <div class="quiz buttons">
            @foreach ($buttonTexts as $buttonText)
                @include('buttons.common', ['subclass' => 'white'])
            @endforeach
        </div>
    @else
        <form id="quiz-form" autocomplete="off">
            @include('inputs.phone')
            <input type="submit" class="peinag button" value="Отправить заявку">
            @include('common.you-agree')
        </form>
    @endisset
</div>
