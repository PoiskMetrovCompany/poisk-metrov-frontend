@php
    $route = request()->get('security');
@endphp

@extends('profile-candidates.layout.app')

@section('content')
    <section>
        <div class="center-card">
            @if ($route)
                <h1>Вход для администратора</h1>
                <p>Введите номер телефона, чтобы авторизоваться в системе и получить доступ к анкете кандидата</p>
                @include('profile-candidates.forms.securityLogin')
                <a href="#" style="display: none;" id="changeNumber">Изменить номер</a>
            @else
                <h1>Регистрация кандидата</h1>
                <p>Введите номер телефона, чтобы авторизоваться в системе и получить доступ к анкете кандидата</p>
                @include('profile-candidates.forms.candidateLogin')
                <a href="#" style="display: none;" id="changeNumber">Изменить номер</a>
            @endif
        </div>
    </section>

    <script>
        const element = document.getElementById('phoneNumber');
        const codeBtn = document.getElementById("getCodeBtn");
        const formLabel = document.getElementById("formLabel");
        const checkboxRow = document.getElementById("checkboxRow");
        const changeNumberLink = document.getElementById("changeNumber");
        const checkmarkIcon = document.getElementById("checkmarkIcon");
        const personalDataCheckbox = document.getElementById("personalData");
        let isPhoneValidated = false;
        let isCodeMode = false;
        let currentMask = null;
        let timerInterval = null;
        let timeLeft = 0;

        const maskOptions = {
            mask: '+{7}(000) 000-00-00'
        };
        currentMask = IMask(element, maskOptions);

        function checkButtonState() {
            if (!isCodeMode) {
                const isPhoneValid = element.value.length >= 17;
                const isCheckboxChecked = personalDataCheckbox.checked;

                if (isPhoneValid && isCheckboxChecked) {
                    codeBtn.classList.remove("btn-inactive");
                    codeBtn.classList.add("btn-active");
                    codeBtn.disabled = false;
                } else {
                    codeBtn.classList.remove("btn-active");
                    codeBtn.classList.add("btn-inactive");
                    codeBtn.disabled = true;
                }
            }
        }

        element.addEventListener("input", function(){
            if(!isCodeMode){
                checkButtonState();
            } else {
                checkCode();
            }
        });

        personalDataCheckbox.addEventListener("change", function() {
            checkButtonState();
        });

        function checkCode() {
            const enteredCode = element.value.replace(/\s/g, '').replace(/_/g, '');

            if (enteredCode === '1234') {
                checkmarkIcon.style.display = 'block';
                console.log('Код введен правильно!');
            } else {
                checkmarkIcon.style.display = 'none';
            }
        }

        codeBtn.addEventListener("click", function(e){
            e.preventDefault();

            if (!isCodeMode) {
                // Переключаемся в режим ввода кода
                startTimer();
                isCodeMode = true;
                changeNumberLink.style.display = "block";
                checkboxRow.style.display = "none";

                if (currentMask){
                    currentMask.destroy();
                }

                formLabel.innerText = "Код из СМС";
                const maskOptions = {
                    mask: ' 0 0 0 0 ',
                    lazy: false,
                    placeholderChar: " _ "
                };
                currentMask = IMask(element, maskOptions);
                element.value = "";
                element.focus();
                element.placeholder = "Введите код из СМС"

                checkmarkIcon.style.display = 'none';
                element.classList.remove('success');
            } else {
                // Повторная отправка кода
                startTimer();
                element.value = "";
                element.focus();

                // Скрываем галочку при повторной отправке
                checkmarkIcon.style.display = 'none';
                element.classList.remove('success');
            }
        });

        function startTimer(){
            timeLeft = 60;
            codeBtn.disabled = true;
            codeBtn.classList.remove("btn-active");
            codeBtn.classList.add('btn-inactive');
            updateButtonText();

            if (timerInterval) {
                clearInterval(timerInterval);
            }

            timerInterval = setInterval(function(){
                timeLeft--;
                updateButtonText();

                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    codeBtn.disabled = false;
                    codeBtn.innerText = "Получить код повторно";
                    codeBtn.classList.remove("btn-inactive");
                    codeBtn.classList.add('btn-active');
                }
            }, 1000);
        }

        function updateButtonText(){
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            codeBtn.innerText = `Получить код повторно ${timeString}`;
        }

        changeNumberLink.addEventListener("click", function(e){
            e.preventDefault();

            isCodeMode = false;

            if (timerInterval) {
                clearInterval(timerInterval);
            }

            changeNumberLink.style.display = "none";
            checkboxRow.style.display = "flex";

            formLabel.innerText = "Телефон";

            if (currentMask) {
                currentMask.destroy();
            }

            const phonesMaskOptions = {
                mask: '+{7}(000) 000-00-00'
            };
            currentMask = IMask(element, phonesMaskOptions);

            element.value = "";
            element.placeholder = "Введите номер";

            codeBtn.classList.remove("btn-active");
            codeBtn.classList.add("btn-inactive");
            codeBtn.disabled = true;
            codeBtn.innerText = "Получить код";

            checkmarkIcon.style.display = 'none';
            element.classList.remove('success');

            element.focus();


            checkButtonState();
        });
    </script>
@endsection
