<?php

namespace App\FeedParsers;

enum FeedFormat: string
{
    //NMarket
    case RealtyFeed = 'realtyfeed';
    //Version 3
    case Avito = 'avito';
    //No name
    case Version2 = 'version2';
    //Feed of complexes with apartments
    case Complexes = 'complexes';
}