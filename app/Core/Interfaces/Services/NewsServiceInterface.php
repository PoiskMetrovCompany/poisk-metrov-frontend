<?php

namespace App\Core\Interfaces\Services;


use App\Models\News;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface NewsServiceInterface
{
    /**
     * @param array $data
     * @param $file
     * @return Model
     */
    public function createOrUpdateArticle(array $data, $file = null): Model;

    /**
     * @param int $id
     */
    public function deleteArticle(int $id);

    /**
     * @param int $id
     * @return Model
     */
    public function getArticle(int $id): Model;

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
