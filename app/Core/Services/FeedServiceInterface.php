<?php

namespace App\Core\Services;

use App\Models\RealtyFeedEntry;
use Illuminate\Support\Collection;

interface FeedServiceInterface
{
    /**
     * @return void
     */
    public function createFeedEntries(): void;

    /**
     * @param array $feedData
     * @return void
     */
    public function createFeedEntry(array $feedData): void;

    /**
     * @param array $feedData
     * @return void
     */
    public function updateFeedEntry(array $feedData): void;

    /**
     * @param array $feedData
     * @return void
     */
    public function deleteFeedEntry(array $feedData): void;

    /**
     * @param array $feedNameData
     * @return void
     */
    public function updateFeedName(array $feedNameData): void;

    /**
     * @return Collection
     */
    public function getFeeds(): Collection;

    /**
     * @return Collection
     */
    public function getFeedNames(): Collection;

    /**
     * @param RealtyFeedEntry $realtyFeedEntry
     * @param bool $log
     * @param bool $ignoreIfExists
     * @return bool
     */
    public function downloadFeed(RealtyFeedEntry $realtyFeedEntry, bool $log = false, bool $ignoreIfExists = false): bool;

    /**
     * @param bool $log
     * @param bool $ignoreIfExists
     * @return void
     */
    public function downloadAllFeeds(bool $log = false, bool $ignoreIfExists = false): void;

    /**
     * @return void
     */
    public function parseAllFeeds(): void;

    /**
     * @return void
     */
    public function mergeFeeds(): void;
}
