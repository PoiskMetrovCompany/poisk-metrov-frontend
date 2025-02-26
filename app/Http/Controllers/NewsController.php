<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleUpdateRequest;
use App\Http\Requests\NewsListRequest;
use App\Http\Requests\RequestById;
use App\Http\Resources\NewsResource;
use App\Services\NewsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class NewsController extends Controller
{
    public function __construct(protected NewsService $newsService)
    {

    }

    public function deleteArticle(Request $request)
    {
        $id = $request->id;

        $this->newsService->deleteArticle($id);
    }

    public function createOrUpdateArticle(ArticleUpdateRequest $articleUpdateRequest)
    {
        $validated = $articleUpdateRequest->validated();
        $validated['author'] = Auth::id();
        $file = $articleUpdateRequest->file('attachment');

        return $this->newsService->createOrUpdateArticle($validated, $file);
    }

    public function getArticle(RequestById $request)
    {
        $id = $request->validated('id');

        return NewsResource::make($this->newsService->getArticle($id));
    }

    public function getNews(Request $request)
    {
        $news = $this->newsService->getNews();

        return NewsResource::collection($news);
    }

    public function getNewsPage(NewsListRequest $request)
    {
        $validated = $request->validated();
        $offset = $validated['offset'];
        $news = $this->newsService->getNews();
        $countPages = ceil(count($news) / 9) + 1;
        $paginator = view('common.paginator-with-show-more', ['id' => 'news-paginator', 'pageCount' => $countPages])->render();
        $news = $this->newsService->getNewsBatch($offset);
        $views = [];

        foreach($news as $card) {
            $views[] = view('common.news-card', [
                'banner' => $card['title_image_file_name'],
                'date' => $card['created_at'],
                'title' => $card['title'],
                'content' => $card['content'],
                'id' => $card['id'],
                'fixed' => ''
            ])->render();
        }

        $components = [
            'views' => $views,
            'paginator' => $paginator
        ];
        
        return $components;
    }

    public function articlePage(Request $request)
    {
        $id = $request['id'];
        $article = $this->newsService->getArticleForSite($id);

        return view('article', [
            'article' => $article['article'],
            'recomended' => $article['recomended']
        ]);
    }
}
