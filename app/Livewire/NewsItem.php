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
        $news = $this->newsService->getByIdOrFail($this->idNews);
        $news->likes()->create(['user_id' => auth()->user()->id]);
        $this->like = true;
    }

    public function dislike()
    {
        $news = $this->newsService->getByIdOrFail($this->idNews);
        $news->likes()->where('user_id', auth()->user()->id)->delete();
        $this->like = false;
    }

    public function addComment()
    {
        if (empty($this->comment)) {
            return redirect()->route('news_item', ['locale' => app()->getLocale(), 'idNews' => $this->idNews])->with('danger', Lang::get('Empty comment'));
        }
        $news = News::findOrFail($this->idNews);
        $news->comments()->create(['user_id' => auth()->user()->id, 'content' => $this->comment]);
        $this->comment = "";
    }

    public function deleteComment($idComment)
    {
        Comment::findOrFail($idComment)->delete();
    }

    public function render()
    {
        $news = News::with(['mainImage', 'likes', 'comments.user'])->find($this->idNews);
        return view('livewire.news-item', compact('news'))->extends('layouts.master')->section('content');
    }
}
