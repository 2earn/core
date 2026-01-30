<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\News;
use App\Models\Comment;
use App\Services\CommentService;
use App\Services\News\NewsService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class NewsItem extends Component
{
    protected NewsService $newsService;
    protected CommentService $commentService;

    public $idNews;
    public $like;
    public $comment;
    public $currentRouteName;

    public function boot(NewsService $newsService, CommentService $commentService)
    {
        $this->newsService = $newsService;
        $this->commentService = $commentService;
    }

    public function mount($idNews)
    {
        $this->idNews = $idNews;
        $this->currentRouteName = Route::currentRouteName();
        $this->like = $this->newsService->hasUserLiked($this->idNews, auth()->user()->id);
    }

    public function like()
    {
        $result = $this->newsService->addLike($this->idNews, auth()->user()->id);
        if ($result) {
            $this->like = true;
        }
    }

    public function dislike()
    {
        $result = $this->newsService->removeLike($this->idNews, auth()->user()->id);
        if ($result) {
            $this->like = false;
        }
    }

    public function addComment()
    {
        if (empty($this->comment)) {
            return redirect()->route('news_item', ['locale' => app()->getLocale(), 'idNews' => $this->idNews])
                ->with('danger', Lang::get('Empty comment'));
        }

        // Add comment using service
        $result = $this->commentService->createComment($this->idNews, auth()->user()->id, $this->comment);

        if ($result['success']) {
            $this->comment = "";
        }
    }

    public function deleteComment($idComment)
    {
        // Delete comment using service
        $this->commentService->deleteComment($idComment);
    }

    public function render()
    {
        // Get news with relationships using service
        $news = $this->newsService->getNewsWithRelations($this->idNews);

        return view('livewire.news-item', compact('news'))
            ->extends('layouts.master')
            ->section('content');
    }
}
