<?php

namespace App\Services;

use App\Models\News;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class NewsService.
 */
class NewsService extends AbstractService
{
    public function createOrUpdateArticle(array $data, $file = null): News
    {
        $article = null;

        if (isset($data['id'])) {
            $article = News::where('id', $data['id'])->first();
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
            $article = News::create($data);
        }

        return $article;
    }

    public function deleteArticle(int $id)
    {
        $article = News::where('id', $id)->first();
        $article->deleteCurrentTitleImage();
        $article->delete();
    }

    public function getArticle(int $id): News
    {
        return News::where("id", $id)->first();
    }

    public function getNews()
    {
        return News::all()->sortBy('created_at')->reverse();
    }

    public static function getFromApp(): NewsService
    {
        return parent::getFromApp();
    }

    public function getArticleForSite(int $id)
    {
        $article = News::where("id", $id)->first();

        if ($article) {
            $article->title_image_file_name = "/news/$article->title_image_file_name";
        }

        $articleData['article'] = $article;

        $articleData['recomended'] = News::where('id', '<>', $id)->limit(3)->get();

        return $articleData;
    }

    public function getNewsBatch(int $offset)
    {
        $news = News::offset($offset)->limit(9)->get()->sortBy('created_at');
        $res = [];

        foreach ($news as $article) {
            $article->title_image_file_name = "/news/$article->title_image_file_name";
            $res[] = $article;
        }

        return $res;
    }

    public function getNewsForSite()
    {
        $news = $this->getNews();
        $res = [];

        foreach ($news as $article) {
            $article->title_image_file_name = "/news/$article->title_image_file_name";
            $res[] = $article;
        }

        return $res;
    }
}
