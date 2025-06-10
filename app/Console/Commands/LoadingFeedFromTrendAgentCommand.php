<?php

namespace App\Console\Commands;

use App\Core\Common\FeedFromTrendAgentFileCoRConst;
use App\Core\Common\FeedFromTrendAgentFileCoREnum;
use App\Core\Interfaces\Repositories\FeedRepositoryInterface;
use App\Services\Handlers\FeedBuilderService;
use App\Services\Handlers\FeedCheckHandlerService;
use App\Services\Handlers\SynchronizationFeedService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class LoadingFeedFromTrendAgentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:loading-feed-from-trend-agent-command {city} {fileName} {extension}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Загрузка фида от Тренд.Агент';

    private function setCity(string $city): void {
        Session::put('city', $city);
        switch ($city) {
            case 'Санкт-Питербург':
                Session::put('cityEng','st-petersburg');
                break;
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $extractPath = storage_path('app/temp_trendagent/');
        $fileName = $this->argument('fileName');
        Session::put('fileName', $fileName);
        $extension = $this->argument('extension');
        $path = Storage::disk('local')->path("temp_trendagent/" . Session::get('fileName') . ".$extension");

        $service = new FeedCheckHandlerService();
        $service->setNext(new FeedBuilderService())
            ->setNext(new SynchronizationFeedService());

        if (!file_exists($path)) {
            Log::error("Файл не найден: $path");
            return;
        }

        Log::info('Распаковка архива');
        switch ($extension) {
            case 'zip':
                $zip = new \ZipArchive;
                if ($zip->open($path) === TRUE) {
                    $zip->extractTo($extractPath);
                    $zip->close();
                    Log::info("ZIP-архив успешно распакован.");
                } else {
                    Log::error("Не удалось открыть ZIP-архив: $path");
                }
                break;

            case 'tar':
                exec("tar -xf {$path}.{$extension} -C {$extractPath}");
                Log::info("TAR-архив распакован.");
                break;

            case 'gz':
            case 'tgz':
                exec("tar -xzf {$path}.{$extension} -C {$extractPath}");
                Log::info("TAR.GZ/TGZ-архив распакован.");
                break;

            case 'bz2':
            case 'tbz':
                exec("tar -xjf {$path}.{$extension} -C {$extractPath}");
                Log::info("TAR.BZ2/TBZ-архив распакован.");
                break;

            case 'rar':
                exec("unrar x {$path}.{$extension} {$extractPath}");
                Log::info("RAR-архив распакован.");
                break;

            case '7z':
                exec("7z x {$path}.{$extension} -o{$extractPath}");
                Log::info("7Z-архив распакован.");
                break;

            default:
                Log::debug("Не поддерживаемый формат архива: $extension");
        }

        foreach (json_decode(Storage::disk('local')->get("temp_trendagent/" . session()->get('fileName') . "/" . FeedFromTrendAgentFileCoREnum::APARTMENTS->value), true) as $item) {
            $service->handle($item);
        }

        Storage::disk('local')->deleteDirectory("temp_trendagent/" . Session::get('fileName'));
    }
}
