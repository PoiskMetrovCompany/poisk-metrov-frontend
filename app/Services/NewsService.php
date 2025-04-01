<?php

namespace App\Services;

use App\Core\Interfaces\Repositories\NewsRepositoryInterface;
use App\Core\Interfaces\Services\NewsServiceInterface;
use App\Models\News;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * @package App\Services
 * @extends AbstractService
 * @implements NewsServiceInterface
 * @property-read NewsRepositoryInterface $newsRepository
 */
final class NewsService extends AbstractService implements NewsServiceInterface
{
    // TODO: возможно возвращаемый тип стоит заменить на "Model"
    public function __construct(protected NewsRepositoryInterface $newsRepository)
    {

    }
    public function createOrUpdateArticle(array $data, $file = null): News
    {
        $article = null;

        if (isset($data['id'])) {
            $article = $this->newsRepository->findById($data['id']);
        }

        if ($file != null) {
            if (! Storage::disk('public_classic')->directoryExists('/news')) {
                Storage::disk('public_classic')->makeDirectory('/news');
            }

            $path = Storage::disk('public_classic')->put('/news', $file);
            $newFileName = explode('/', $path);
            $newFileName = $newFileName[count($newFileName) - 1];
            $data['title_image_file_name'] = $newFileName;
        }

        if ($article != null) {
            if ($file != null) {
                $article->deleteCurrentTitleImage();
            }

            $article->update($data);
        } else {
            $article = $this->newsRepository->store($data);
        }

        return $article;
    }

    public function deleteArticle(int $id)
    {
        $article = $this->newsRepository->findById($id);
        $article->deleteCurrentTitleImage();
        $article->delete();
    }

    public function getArticle(int $id): News
    {
        return $this->newsRepository->findById($id);
    }

    public function getNews(): Collection
    {
        // TODO: этот метод вообще ненужен, он избыточен
        return News::all()->sortBy('created_at')->reverse();
    }

    public function getArticleForSite(int $id): array
    {
        $article = $this->newsRepository->findById($id);

        if ($article) {
            $article->title_image_file_name = "/news/$article->title_image_file_name";
        }

        $articleData['article'] = $article;
        // TODO: с этим тоже непонятно что делать...
        $articleData['recomended'] = News::where('id', '<>', $id)->limit(3)->get();

        return $articleData;
    }

    public function getNewsBatch(int $offset): array
    {
        // TODO: подумать что с этим делать, возможно стоит создать метод
        $news = News::offset($offset)->limit(9)->get()->sortBy('created_at');
        $res = [];

        foreach ($news as $article) {
            $article->title_image_file_name = "/news/$article->title_image_file_name";
            $res[] = $article;
        }

        return $res;
    }

    public function getNewsForSite(): array
    {
        $news = $this->getNews();
        $res = [];

        foreach ($news as $article) {
            $article->title_image_file_name = "/news/$article->title_image_file_name";
            $res[] = $article;
        }

        return $res;
    }

    public static function getFromApp(): NewsService
    {
        return parent::getFromApp();
    }
}
