<?php

namespace App\Core\Interfaces\Services;


use App\Models\News;
use Illuminate\Support\Collection;

interface NewsServiceInterface
{
    /**
     * @param array $data
     * @param $file
     * @return News
     */
    public function createOrUpdateArticle(array $data, $file = null): News;

    /**
     * @param int $id
     */
    public function deleteArticle(int $id);

    /**
     * @param int $id
     * @return News
     */
    public function getArticle(int $id): News;

    /**
     * @return Collection
     */
    public function getNews(): Collection;

    /**
     * @param int $id
     * @return array
     */
    public function getArticleForSite(int $id): array;

    /**
     * @param int $offset
     * @return array
     */
    public function getNewsBatch(int $offset): array;

    /**
     * @return array
     */
    public function getNewsForSite(): array;
}
