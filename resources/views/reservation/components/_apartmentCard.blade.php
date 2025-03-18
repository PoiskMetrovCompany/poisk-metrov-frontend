@php use Carbon\Carbon; @endphp
<nav class="apartment-grid">
    @foreach($menuApartmentTitle as $item)
        <div class="apartment-col @if($item === 'Жилой комплекс') apartment-col__15 @else apartment-col__10 @endif">
            {{ $item }}
        </div>
    @endforeach
</nav>

@for($i=0; $countApartments > $i; $i++)
    @if ($i == 0)
        <section class="apartment-grid apartment-grid__wrap apartment-card apartment-top__round">
    @elseif (($i + 1)== $countApartments)
        <section class="apartment-grid apartment-grid__wrap apartment-card apartment-bottom__round">
    @else
        <section class="apartment-grid apartment-grid__wrap apartment-card">
    @endif
        <div class="apartment-col apartment-col__15">{{ $apartmentsList[$i]['complexes']['name'] }}</div>
        <div class="apartment-col apartment-col__10">{{ $apartmentsList[$i]['complexes']['builder'] }}</div>
        <div class="apartment-col apartment-col__10">{{ $apartmentsList[$i]['apartment']['offer_id'] }}</div>
            @php
                $format = Carbon::parse($apartmentsList[$i]['apartment']['created_at']);
                $formattedDate = $format->format('d.m.Y');
            @endphp
        <div class="apartment-col apartment-col__10">{{ $formattedDate }}</div>
        <div class="apartment-col apartment-col__10">
            @if($apartmentsList[$i]['apartment']['apartment_type'] === 'Студия')
                {{ $apartmentsList[$i]['apartment']['apartment_type'] }}
            @else
                {{ $apartmentsList[$i]['apartment']['room_count'] }}
            @endif
        </div>
        <div class="apartment-col apartment-col__10">{{ number_format($apartmentsList[$i]['apartment']['price'], 0, '.', ' ') }} ₽</div>
        <div class="apartment-col apartment-col__10">{{ $apartmentsList[$i]['apartment']['area'] }} ₽/м2</div>
            @php
                $format = Carbon::parse($apartmentsList[$i]['created_at']);
                $formattedDate = $format->format('d.m.Y');
            @endphp
        <div class="apartment-col apartment-col__10">{{ $formattedDate }}</div>
        <div class="apartment-col apartment-col__10">
            <a href="#" class="apartment-card__href">Подробнее</a>
        </div>
    </section>
@endfor

<section class="apartment-grid-min">
    @for($i=0; $countApartments > $i; $i++)
        @if ($i < 2) <!-- TODO: пока ограничу вывод в 2 элемента, непонятно как должен вести себя список если там больше 2х элементов -->
            <div class="apartment-grid-container">
        <table>
            <tbody>
            <tr>
                <td class="apartment-grid-min__label">Проект</td>
                <td>ЖК {{ $apartmentsList[$i]['complexes']['name'] }}</td>
            </tr>
            <tr>
                <td class="apartment-grid-min__label">Номер заявки</td>
                <td>{{ $apartmentsList[$i]['apartment']['offer_id'] }}</td>
            </tr>
            <tr>
                <td class="apartment-grid-min__label">Кол-во комнат</td>
                <td>
                    @if($apartmentsList[$i]['apartment']['apartment_type'] === 'Студия')
                        {{ $apartmentsList[$i]['apartment']['apartment_type'] }}
                    @else
                        {{ $apartmentsList[$i]['apartment']['room_count'] }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="apartment-grid-min__label">Стоимость</td>
                <td>{{ number_format($apartmentsList[$i]['apartment']['price'], 0, '.', ' ') }} ₽</td>
            </tr>
            <tr>
                <td class="apartment-grid-min__label">
                    <a href="#" class="apartment-card__href">
                        Подробнее
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
        @endif
    @endfor
</section>

<section class="apartment-grid-phone">
    <div class="apartment-grid-row">
        @for($i=0; $countApartments > $i; $i++)
            <!-- TODO: Возможно тут надо тоже ограничить кол-во броней -->
            <div class="apartment-grid-phone__container">
            <section>
                <div class="apartment-grid-phone__container-row">
                    {{-- TODO: Все SVG картинки вынести в отдельные файлы, указывать только пути --}}
                    <svg width="44" height="46" viewBox="0 0 44 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_1641_16706)">
                            <rect y="0.730469" width="44" height="44.5404" rx="8" fill="url(#paint0_radial_1641_16706)"/>
                            <path d="M20.1597 15.5195H6.16797V68.5832H20.1597V15.5195Z" fill="#EBEBEB"/>
                            <path d="M20.1588 12H8.15723V15.5193H20.1588V12Z" fill="#C7C7C7"/>
                            <path d="M18.6258 17.3555H7.70215V57.024H18.6258V17.3555Z" fill="#DBDBDB"/>
                            <path d="M18.6258 21.1406H7.70215V22.078H18.6258V21.1406Z" fill="white"/>
                            <path d="M18.6258 25.0234H7.70215V25.9609H18.6258V25.0234Z" fill="white"/>
                            <path d="M18.6258 28.9062H7.70215V29.8437H18.6258V28.9062Z" fill="white"/>
                            <path d="M18.6258 32.7891H7.70215V33.7265H18.6258V32.7891Z" fill="white"/>
                            <path d="M18.6258 36.6719H7.70215V37.6093H18.6258V36.6719Z" fill="white"/>
                            <path d="M18.6258 40.5547H7.70215V41.4921H18.6258V40.5547Z" fill="white"/>
                            <path d="M18.6258 44.4375H7.70215V45.3749H18.6258V44.4375Z" fill="white"/>
                            <path d="M28.9241 15.5195H20.1602V68.5832H28.9241V15.5195Z" fill="#DBDBDB"/>
                            <path d="M27.6087 12H20.1602V15.5193H27.6087V12Z" fill="#A6A6A6"/>
                            <path d="M28.1062 17.3555H21.3682V57.024H28.1062V17.3555Z" fill="#C7C7C7"/>
                            <path d="M28.1062 21.1406H21.3682V22.078H28.1062V21.1406Z" fill="white"/>
                            <path d="M28.1062 25.0234H21.3682V25.9609H28.1062V25.0234Z" fill="white"/>
                            <path d="M28.1062 28.9062H21.3682V29.8437H28.1062V28.9062Z" fill="white"/>
                            <path d="M28.1062 32.7891H21.3682V33.7265H28.1062V32.7891Z" fill="white"/>
                            <path d="M28.1062 36.6719H21.3682V37.6093H28.1062V36.6719Z" fill="white"/>
                            <path d="M28.1062 40.5547H21.3682V41.4921H28.1062V40.5547Z" fill="white"/>
                            <path d="M28.1062 44.4375H21.3682V45.3749H28.1062V44.4375Z" fill="white"/>
                            <path d="M30.5496 65.0961H15.2012V16.9473L30.5496 7V65.0961Z" fill="#E46219"/>
                            <path opacity="0.7" d="M30.5496 65.0961H15.2012V16.9473L30.5496 7V65.0961Z" fill="white"/>
                            <path d="M28.5769 65.0973H16.9287V18.2955L28.5769 10.7461V65.0973Z" fill="#E46219"/>
                            <path d="M28.5769 65.0973H16.9287V18.2955L28.5769 10.7461V65.0973Z" fill="#D2D2D2"/>
                            <g opacity="0.1">
                                <path d="M28.5742 13.0625V16.431L16.9268 28.0787V24.7075L28.5742 13.0625Z" fill="white"/>
                                <path d="M28.5742 43.3672V46.7384L16.9268 58.3861V55.0149L28.5742 43.3672Z" fill="white"/>
                                <path d="M28.5742 19.2461V20.9359L16.9268 32.5809V30.8965L28.5742 19.2461Z" fill="white"/>
                                <path d="M28.5742 40.3242V42.014L16.9268 53.659V51.9746L28.5742 40.3242Z" fill="white"/>
                                <path d="M28.5742 33.2461V33.6617L16.9268 45.3094V44.8938L28.5742 33.2461Z" fill="white"/>
                            </g>
                            <path d="M28.5749 19.1016H18.2607V19.4726H28.5749V19.1016Z" fill="#E46219"/>
                            <path d="M28.5749 22.1484H18.2607V22.5195H28.5749V22.1484Z" fill="#E46219"/>
                            <path d="M28.5749 25.1875H18.2607V25.5585H28.5749V25.1875Z" fill="#E46219"/>
                            <path d="M28.5749 28.2266H18.2607V28.5976H28.5749V28.2266Z" fill="#E46219"/>
                            <path d="M28.5749 31.2695H18.2607V31.6406H28.5749V31.2695Z" fill="#E46219"/>
                            <path d="M28.5749 34.3125H18.2607V34.6835H28.5749V34.3125Z" fill="#E46219"/>
                            <path d="M28.5749 37.3516H18.2607V37.7226H28.5749V37.3516Z" fill="#E46219"/>
                            <path d="M28.5749 40.3906H18.2607V40.7617H28.5749V40.3906Z" fill="#E46219"/>
                            <path d="M28.5749 43.4375H18.2607V43.8085H28.5749V43.4375Z" fill="#E46219"/>
                            <path d="M23.2324 15.5715L23.2324 65.0977H23.6034L23.6034 15.5715H23.2324Z" fill="#E46219"/>
                            <path d="M28.5759 10.7461V12.4214L18.2617 19.106V65.0975H16.9287V18.2948L28.5759 10.7461Z" fill="#E46219"/>
                            <path opacity="0.1" d="M28.5759 10.7461V12.4214L18.2617 19.106V65.0975H16.9287V18.2948L28.5759 10.7461Z" fill="black"/>
                            <path d="M44.0706 7H30.5488V65.0961H44.0706V7Z" fill="#E46219"/>
                            <path opacity="0.2" d="M44.0706 7H30.5488V65.0961H44.0706V7Z" fill="white"/>
                            <path d="M42.7574 10.7461H31.8594V12.0609H42.7574V10.7461Z" fill="#D2D2D2"/>
                            <path d="M42.7574 13.7266H31.8594V15.0414H42.7574V13.7266Z" fill="#D2D2D2"/>
                            <path d="M42.7574 16.7031H31.8594V18.018H42.7574V16.7031Z" fill="#D2D2D2"/>
                            <path d="M42.7574 19.6797H31.8594V20.9945H42.7574V19.6797Z" fill="#D2D2D2"/>
                            <path d="M42.7574 22.6641H31.8594V23.9789H42.7574V22.6641Z" fill="#D2D2D2"/>
                            <path d="M42.7574 25.6445H31.8594V26.9594H42.7574V25.6445Z" fill="#D2D2D2"/>
                            <path d="M42.7574 28.6211H31.8594V29.9359H42.7574V28.6211Z" fill="#D2D2D2"/>
                            <path d="M42.7574 31.6016H31.8594V32.9164H42.7574V31.6016Z" fill="#D2D2D2"/>
                            <path d="M42.7574 34.582H31.8594V35.8969H42.7574V34.582Z" fill="#D2D2D2"/>
                            <path d="M42.7574 37.5586H31.8594V38.8734H42.7574V37.5586Z" fill="#D2D2D2"/>
                            <path d="M42.7574 40.5391H31.8594V41.8539H42.7574V40.5391Z" fill="#D2D2D2"/>
                            <path d="M42.7574 43.5195H31.8594V44.8344H42.7574V43.5195Z" fill="#D2D2D2"/>
                        </g>
                        <defs>
                            <radialGradient id="paint0_radial_1641_16706" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(22 23.0006) rotate(90) scale(27.0003 26.6728)">
                                <stop stop-color="white"/>
                                <stop offset="1" stop-color="#FFD4BC"/>
                            </radialGradient>
                            <clipPath id="clip0_1641_16706">
                                <rect y="0.730469" width="44" height="44.5404" rx="8" fill="white"/>
                            </clipPath>
                        </defs>
                    </svg>
                    {{-- END --}}
                </div>
                <div class="apartment-grid-phone__container-row">
                    <p>ЖК {{ $apartmentsList[$i]['complexes']['name'] }}</p>
                    <p>
                        @if($apartmentsList[$i]['apartment']['apartment_type'] === 'Студия')
                            {{ $apartmentsList[$i]['apartment']['apartment_type'] }}
                        @else
                            {{ $apartmentsList[$i]['apartment']['room_count'] }} к.кв
                        @endif
                    </p>
                </div>
                <div class="apartment-grid-phone__container-row apartment-grid-phone__container-row-price">
                    <p>{{ number_format($apartmentsList[$i]['apartment']['price'], 0, '.', ' ') }} ₽</p>
                </div>
            </section>
        </div>
        @endfor
    </div>
</section>

