<div class="action-accordion">
    @for($i=0; $i < count($accordions); $i++)
        <div class="action-accordion__item">

            @if ($i != 0) <div class="action-accordion__item-top"></div> @endif

            <div class="action-accordion__item-container">
                <p>{{ $accordions[$i]['title'] }}</p>
                <span class="active">Заполните</span>
            </div>
            <div class="action-accordion__item-control" data-control="{{ $accordions[$i]['key'] }}">
                <svg width="7" height="10" viewBox="0 0 7 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 8.5L5 5L1 1.5" stroke="#EC7D3F" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </div>
        </div>
        <section class="action-accordion-user-info"
                 id="idx-{{ $accordions[$i]['key'] }}">
            @include('reservation.components._formFields', ['fields' => $accordions[$i]['fields']])

            @if (!empty($accordions[$i]['dropdown']))
                @if ($accordions[$i]['title'] === 'Документы' && key_exists('passport', $accordions[$i]))
                    <section class="action-accordion-user-info__sub-dropdown">
                        <p class="action-accordion-user-info__dropdown-large"
                           data-sub-dropdown="passport">
                            <svg width="24" height="20" viewBox="0 0 24 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21.6632 5.33465H21.3334V4.2271C21.3328 3.69591 21.1215 3.18665 20.7459 2.81105C20.3703 2.43544 19.861 2.22416 19.3298 2.22354H12.4807L12.2567 1.77554C12.0915 1.44182 11.8359 1.16114 11.5192 0.965356C11.2024 0.769576 10.8371 0.666551 10.4647 0.667983H2.33693C1.80574 0.668601 1.29649 0.879887 0.920884 1.25549C0.545278 1.6311 0.333992 2.14035 0.333374 2.67154V17.3311C0.333992 17.8623 0.545278 18.3715 0.920884 18.7471C1.29649 19.1227 1.80574 19.334 2.33693 19.3347H21.6632C22.1943 19.334 22.7036 19.1227 23.0792 18.7471C23.4548 18.3715 23.6661 17.8623 23.6667 17.3311V7.33821C23.6661 6.80702 23.4548 6.29777 23.0792 5.92216C22.7036 5.54656 22.1943 5.33527 21.6632 5.33465ZM19.3298 3.77909C19.5764 3.77909 19.7778 3.97976 19.7778 4.2271V5.33465H14.3132C14.2299 5.3351 14.1482 5.31216 14.0774 5.26842C14.0066 5.22469 13.9495 5.16194 13.9126 5.08732L13.2585 3.77909H19.3298ZM22.1112 17.3311C22.1109 17.4499 22.0637 17.5637 21.9797 17.6477C21.8957 17.7316 21.7819 17.7789 21.6632 17.7791H2.33693C2.21818 17.7789 2.10434 17.7316 2.02037 17.6477C1.9364 17.5637 1.88914 17.4499 1.88893 17.3311V2.67154C1.88893 2.42421 2.09037 2.22354 2.33693 2.22354H10.4647C10.6358 2.22354 10.789 2.31843 10.8653 2.47087L12.5212 5.78265C12.6864 6.11637 12.9419 6.39705 13.2587 6.59283C13.5754 6.78861 13.9408 6.89164 14.3132 6.89021H21.6632C21.9097 6.89021 22.1112 7.09087 22.1112 7.33821V17.3311Z" fill="#181817"/>
                            </svg>
                            Паспорт<br/>
                            <small>9 разворотов</small>
                        </p>
                        @include('inputs.card-file-reservation')
                    </section>
                @endif
                <section class="action-accordion-user-info__sub-dropdown">

                    @if (key_exists('leftTitleIcon', $accordions[$i]))
                        <p id="info__sub-dropdown"
                           class="action-accordion-user-info__dropdown-large"
                           data-sub-dropdown="{{ $accordions[$i]['dropdown']['key'] }}">
                            <svg width="24" height="20" viewBox="0 0 24 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21.6632 5.33465H21.3334V4.2271C21.3328 3.69591 21.1215 3.18665 20.7459 2.81105C20.3703 2.43544 19.861 2.22416 19.3298 2.22354H12.4807L12.2567 1.77554C12.0915 1.44182 11.8359 1.16114 11.5192 0.965356C11.2024 0.769576 10.8371 0.666551 10.4647 0.667983H2.33693C1.80574 0.668601 1.29649 0.879887 0.920884 1.25549C0.545278 1.6311 0.333992 2.14035 0.333374 2.67154V17.3311C0.333992 17.8623 0.545278 18.3715 0.920884 18.7471C1.29649 19.1227 1.80574 19.334 2.33693 19.3347H21.6632C22.1943 19.334 22.7036 19.1227 23.0792 18.7471C23.4548 18.3715 23.6661 17.8623 23.6667 17.3311V7.33821C23.6661 6.80702 23.4548 6.29777 23.0792 5.92216C22.7036 5.54656 22.1943 5.33527 21.6632 5.33465ZM19.3298 3.77909C19.5764 3.77909 19.7778 3.97976 19.7778 4.2271V5.33465H14.3132C14.2299 5.3351 14.1482 5.31216 14.0774 5.26842C14.0066 5.22469 13.9495 5.16194 13.9126 5.08732L13.2585 3.77909H19.3298ZM22.1112 17.3311C22.1109 17.4499 22.0637 17.5637 21.9797 17.6477C21.8957 17.7316 21.7819 17.7789 21.6632 17.7791H2.33693C2.21818 17.7789 2.10434 17.7316 2.02037 17.6477C1.9364 17.5637 1.88914 17.4499 1.88893 17.3311V2.67154C1.88893 2.42421 2.09037 2.22354 2.33693 2.22354H10.4647C10.6358 2.22354 10.789 2.31843 10.8653 2.47087L12.5212 5.78265C12.6864 6.11637 12.9419 6.39705 13.2587 6.59283C13.5754 6.78861 13.9408 6.89164 14.3132 6.89021H21.6632C21.9097 6.89021 22.1112 7.09087 22.1112 7.33821V17.3311Z" fill="#181817"/>
                            </svg>
                            Подтверждение дохода
                            <span>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 9.29289C5.68342 8.90237 6.31658 8.90237 6.70711 9.29289L12 14.5858L17.2929 9.29289C17.6834 8.90237 18.3166 8.90237 18.7071 9.29289C19.0976 9.68342 19.0976 10.3166 18.7071 10.7071L12.7071 16.7071C12.3166 17.0976 11.6834 17.0976 11.2929 16.7071L5.29289 10.7071C4.90237 10.3166 4.90237 9.68342 5.29289 9.29289Z" fill="#9E9E9E"/>
                                </svg>
                            </span>
                        </p>
                    @else
                        <p id="info__sub-dropdown" data-sub-dropdown="{{ $accordions[$i]['dropdown']['key'] }}">
                            {{ $accordions[$i]['dropdown']['title'] }}
                            <span>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 9.29289C5.68342 8.90237 6.31658 8.90237 6.70711 9.29289L12 14.5858L17.2929 9.29289C17.6834 8.90237 18.3166 8.90237 18.7071 9.29289C19.0976 9.68342 19.0976 10.3166 18.7071 10.7071L12.7071 16.7071C12.3166 17.0976 11.6834 17.0976 11.2929 16.7071L5.29289 10.7071C4.90237 10.3166 4.90237 9.68342 5.29289 9.29289Z" fill="#EC7D3F"/>
                                </svg>
                            </span>
                        </p>
                    @endif

                    <section class="action-accordion-user-info sub-menu"
                             id="idx-{{ $accordions[$i]['dropdown']['key'] }}">
                        @include('reservation.components._formFields', ['fields' => $accordions[$i]['dropdown']])
                    </section>
                    @if ($accordions[$i]['title'] === 'Документы')
                        @include('inputs.card-file-reservation')
                    @endif
                </section>
            @endif
        </section>
    @endfor
</div>
