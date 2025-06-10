<?php

namespace App\Core\Abstracts;

use App\Core\Common\FeedFromTrendAgentFileCoREnum;
use App\Core\Interfaces\Services\HandlerInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

// TODO: при рефакторинге вернуться и разрешить проблемы этого класса
abstract class AbstractHandler implements HandlerInterface
{
    protected ?HandlerInterface $nextHandler = null;

    private function findByKeySearch(?array $data, string $targetKey) {
        $quickSort = function(&$arr) use (&$quickSort) {
            if (count($arr) <= 1) {
                return $arr;
            }

            $pivot = $arr[0];
            $left = [];
            $right = [];

            for ($i = 1; $i < count($arr); $i++) {
                if ($arr[$i]["_id"] < $pivot["_id"]) {
                    $left[] = $arr[$i];
                } else {
                    $right[] = $arr[$i];
                }
            }

            return array_merge($quickSort($left), [$pivot], $quickSort($right));
        };

        $binarySearch = function($arr, $targetKey) {
            $low = 0;
            $high = count($arr) - 1;

            while ($low <= $high) {
                $mid = (int)(($low + $high) / 2);
                if ($arr[$mid]["_id"] === $targetKey) {
                    return $arr[$mid];
                } elseif ($arr[$mid]["_id"] < $targetKey) {
                    $low = $mid + 1;
                } else {
                    $high = $mid - 1;
                }
            }
            return null;
        };

        if (empty($data)) {
            return null;
        }

        $sortedData = $quickSort($data);
        return $binarySearch($sortedData, $targetKey);
    }

    public function setNext(HandlerInterface $handler): HandlerInterface
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(?array $attributes): ?array
    {
        if ($this->nextHandler) {
            return $this->nextHandler->handle($attributes);
        }

        return null;
    }

    /**
     * @param string $builderKey
     * @return array|null
     */
    public function readFeedBuilderFile(string $builderKey): ?array
    {
        $file = Storage::disk('local')->get("temp_trendagent/" . Session::get('fileName') . "/" . FeedFromTrendAgentFileCoREnum::BUILDER->value);
        $data = json_decode($file, true);

        return $this->findByKeySearch($data, $builderKey);
    }

    /**
     * @param string $detailsBlockKey
     * @return array|null
     */
    public function readFeedDetailFile(string $detailsBlockKey): ?array
    {
        $file = Storage::disk('local')->get("temp_trendagent/" . session()->get('fileName') . "/" . FeedFromTrendAgentFileCoREnum::BLOCKS->value);
        $data = json_decode($file, true);
        return $this->findByKeySearch($data, $detailsBlockKey);
    }

    public function readFeedSubwayFile(array $subway)
    {
        $file = Storage::disk('local')->get("temp_trendagent/" . session()->get('fileName') . "/" . FeedFromTrendAgentFileCoREnum::SUBWAYS->value);
        $data = json_decode($file, true);

        foreach ($subway as $item) {
            $arr[] = [
                ...$this->findByKeySearch($data, $item['subway_id']),
                ...$item
            ];
        }

        return $arr ?? [];
    }

    public function readFeedBuildingFile(string $buildingKey)
    {
        $file = Storage::disk('local')->get("temp_trendagent/" . session()->get('fileName') . "/" . FeedFromTrendAgentFileCoREnum::BUILDINGS->value);
        $data = json_decode($file, true);
        return $this->findByKeySearch($data, $buildingKey);
    }

    public function readFeedBuildingTypeFile(string $buildingTypeKey)
    {
        $file = Storage::disk('local')->get("temp_trendagent/" . session()->get('fileName') . "/" . FeedFromTrendAgentFileCoREnum::BUILDING_TYPES->value);
        $data = json_decode($file, true);
        return $this->findByKeySearch($data, $buildingTypeKey);
    }

    public function readFeedFinishingFile(string $finishingKey)
    {
        $file = Storage::disk('local')->get("temp_trendagent/" . session()->get('fileName') . "/" . FeedFromTrendAgentFileCoREnum::FINISHINGS->value);
        $data = json_decode($file, true);
        return $this->findByKeySearch($data, $finishingKey);
    }

    public function readFeedRegionFile(string $regionKey)
    {
        $file = Storage::disk('local')->get("temp_trendagent/" . session()->get('fileName') . "/" . FeedFromTrendAgentFileCoREnum::REGIONS->value);
        $data = json_decode($file, true);
        return $this->findByKeySearch($data, $regionKey);
    }
}
