<fieldset class="input-fieldset">
    <legend class="input-legend">{{ $legend }}</legend>
    <div class="mortgage-calculator sliders inside">
        <div class="mortgage-calculator sliders values">
            <input id="{{ $textInputId }}" type="text" />
            <div id="{{ $maxValueDisplay }}" class="mortgage-calculator sliders percent">-</div>
        </div>
        <input id="{{ $sliderId }}" class="filters number-input range" type="range">
    </div>
</fieldset>
