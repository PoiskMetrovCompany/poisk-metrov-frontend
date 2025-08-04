<div id="{{ $id }}" class="arrow-buttons-container {{$spaced ?? ''}}" >
    @include('buttons.arrow-left', ['isGrey' => $spaced ?? ''])
    @include('buttons.arrow-right')
</div>
