<?php

namespace App\Services\Parser;

use App\Core\Interfaces\Services\ParserCbrServiceInterface;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @package App\Services\Parser
 * @implements ParserCbrServiceInterface
 * @property-read string $url
 * @property-read string $filePath
 */
final class ParserCbrService implements ParserCbrServiceInterface
{
    protected string $url;
    protected string $filePath = 'cbr.json';
    public function __construct()
    {
        $this->url = 'https://www.cbr.ru/dkp/cal_mp/#t12';
    }

    public function getHistoryAll(): array
    {
        $content = Storage::disk('local')->get($this->filePath);
        return json_decode($content, true);
    }

    public function saved(array $attributes): void
    {
        if (!Storage::disk('local')->exists($this->filePath)) {
            Storage::disk('local')->put($this->filePath, json_encode([]));
        }

        $content = $this->getHistoryAll();
        $content[] = [
            'day_cbr' => $attributes['day'],
            'month_cbr' => $attributes['month'],
            'year_cbr' => $attributes['year'],
            'created_at' => Carbon::now(),
        ];
        Storage::disk('local')->put($this->filePath, json_encode($content, JSON_PRETTY_PRINT));
    }

    public function handle(): void
    {
        $client = new Client();
        $response = $client->request('GET', $this->url);
        $htmlContent = $response->getBody()->getContents();
        $crawler = new Crawler($htmlContent);
        $year = Carbon::now()->format('Y');

        $crawler->filter('div.main-events_day')->each(function (Crawler $node) use ($year) {
            $nodeValue = trim($node->text());
            if (
                strpos($nodeValue, (string)$year) !== false
                && strpos($nodeValue, 'Заседание Совета директоров Банка') !== false
            ) {
                $node = explode(' ', explode(' ', $nodeValue)[0]);
                $months = [
                    'января' => '01',
                    'февраля' => '02',
                    'марта' => '03',
                    'апреля' => '04',
                    'мая' => '05',
                    'июня' => '06',
                    'июля' => '07',
                    'августа' => '08',
                    'сентября' => '09',
                    'октября' => '10',
                    'ноября' => '11',
                    'декабря' => '12'
                ];

                $this->saved([
                    'day' => $node[0],
                    'month' => $months[$node[1]],
                    'year' => $year
                ]);
            }
        });
    }
}
