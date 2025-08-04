<?php

namespace App\Core\Common\Banks;

final class BanksData
{
    //Смотреть в запросе при нажатии на кнопку Показать еще
    public static array $regionIdsForCities = [
        'novosibirsk' => 677,
        'st-petersburg' => 211
    ];
    //В базе данных есть ипотеки меньше 20, но мы берем только актуальные
    public static int $minInitialFee = 20;
    public static array $mortgageTypesExceptions = ['Дальневосточная', 'Арктическая'];
    public static array $preferredBanks = [
        'Альфа-Банк',
        'Совкомбанк',
        'Банк ДОМ.РФ',
        'РНКБ',
        'ПСБ',
        'Росбанк',
        'ВТБ',
        'Уралсиб',
        'Абсолют Банк',
        'Ак Барс Банк',
        'Россельхозбанк',
        'МТС Банк',
        'Банк ТКБ',
        'Банк Жилищного Финансирования',
        'Банк Акцепт',
        'Газпромбанк',
        'Банк «РОССИЯ»',
        'Банк «Санкт-Петербург»',
        'Металлинвестбанк',
        'Почта Банк',
        'Банк «Левобережный»'
    ];
}
