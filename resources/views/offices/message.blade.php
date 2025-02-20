<div class="offices message container">
    <div class="offices message description">
        <img src="{{ Vite::asset('resources/assets/employees/photos/malikova-1.png') }}"
            class="offices message image">
        <div class="offices message text">
            <div class="offices message text-item">
                Я – Маликова Юлия, руководитель офиса Поиска метров.
                <br>
                Моя цель – создавать для клиента комфортные условия взаимодействия с нашей компанией в любой момент
                времени.
                <br>
                <br>
                Если у вас есть нерешенные вопросы, напишите мне, и я лично разберусь в Вашей ситуации.
            </div>
            <div class="offices message signature">
                <div class="offices message name">Юлия Маликова</div>
                <div class="offices message job">руководитель офиса Поиска метров</div>
            </div>
        </div>
    </div>
    <form autocomplete="off" id="left-message" class="offices message form">
        @csrf
        @include('inputs.name', ['required' => '*'])
        @include('inputs.phone', ['required' => '*'])
        @include('inputs.message')
        @include('common.personal-info-agreement', ['buttonText' => 'Отправить&nbsp;сообщние'])
    </form>
    <div class="offices message result">
        <div class="thanks-for-contacts success">Ваше сообщение успешно отправлено!</div>
        <div class="thanks-for-contacts failure">
            <div class="thanks-for-contacts error">
                <div class="thanks-for-contacts error-icon">
                    <div class="icon action-close d20x20 white"></div>
                </div>
                Ошибка
            </div>
            Произошла ошибка, обновите страницу позднее
        </div>
    </div>
</div>