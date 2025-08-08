<?php

namespace App\Console\Commands;
set_time_limit(0);

use App\Core\Common\FeedFromTrendAgentFileCoRConst;
use App\Core\Common\FeedFromTrendAgentFileCoREnum;
use App\Core\Interfaces\Repositories\FeedRepositoryInterface;
use App\Models\Journal;
use App\Services\Handlers\FeedBuilderService;
use App\Services\Handlers\FeedCheckHandlerService;
use App\Services\Handlers\SynchronizationFeedService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use JsonMachine\JsonDecoder;
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
            // TODO: добавить остальныфе города таким же образом
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->setCity($this->argument('city'));
        $extractPath = storage_path('app/public/temp-feed');
        $fileName = $this->argument('fileName');
        Session::put('fileName', $fileName);
        $extension = $this->argument('extension');
        $path = Storage::disk('local')->path("public/temp-feed/" . Session::get('fileName') . ".$extension");

        $service = new FeedCheckHandlerService();
        $service->setNext(new FeedBuilderService())
            ->setNext(new SynchronizationFeedService());

        if (!file_exists($path)) {
            return;
        }

        switch ($extension) {
            case 'zip':
                $zip = new \ZipArchive;
                if ($zip->open($path) === TRUE) {
                    $zip->extractTo($extractPath);
                    $zip->close();
                } else {
                    Log::error("Не удалось открыть ZIP-архив: $path");
                }
                break;

            case 'tar':
                exec("tar -xf {$path}.{$extension} -C {$extractPath}");
                break;

            case 'gz':
            case 'tgz':
                exec("tar -xzf {$path}.{$extension} -C {$extractPath}");
                break;

            case 'bz2':
            case 'tbz':
                exec("tar -xjf {$path}.{$extension} -C {$extractPath}");
                break;

            case 'rar':
                exec("unrar x {$path}.{$extension} {$extractPath}");
                break;

            case '7z':
                exec("7z x {$path}.{$extension} -o{$extractPath}");
                break;

            default:
                Log::debug("Не поддерживаемый формат архива: $extension");
        }

        $feedData = json_decode(Storage::disk('local')->get("public/temp-feed/" . session()->get('fileName') . "/" . FeedFromTrendAgentFileCoREnum::APARTMENTS->value), true);
        Session::put('feedDataLength', count($feedData));
        Session::put('synchronizeKeySession', Str::uuid()->toString());

        Journal::create([
            'key' => Session::get('synchronizeKeySession'),
            'type' => 'synchronizeFeed',
            'status' => 'В обработке',
            'name' => Session::get('fileName'),
            'found_objects' => (int)Session::get('feedDataLength'),
            'loaded_objects' => 0,
        ]);
        foreach ($feedData as $item) {
            $service->handle($item);
            unset($item);
        }

        Storage::disk('local')->deleteDirectory("public/temp-feed/" . Session::get('fileName'));
        Session::remove('city');
        Session::remove('cityEng');
        Session::remove('fileName');
    }
}
