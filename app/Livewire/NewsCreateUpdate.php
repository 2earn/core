<?php

namespace App\Livewire;

use App\Models\News;
use App\Models\TranslaleModel;
use App\Models\Hashtag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class NewsCreateUpdate extends Component
{
    use WithFileUploads;

    public $idNews;
    public $update;
    public $enabled;
    public $title, $content, $published_at;
    public $mainImage;
    public $allHashtags = [];
    public $selectedHashtags = [];

    protected $rules = [
        'title' => 'required',
        'content' => 'required',
        'mainImage' => 'nullable|image|mimes:jpeg,png,jpg',
    ];

    public function mount(Request $request)
    {
        $this->allHashtags = Hashtag::all();
        $this->selectedHashtags = [];
        $this->idNews = $request->input('id');
        if (!is_null($this->idNews)) {
            $this->edit($this->idNews);
        } else {
            $this->enabled = false;
        }
    }

    public function cancel()
    {
        return redirect()->route('news_index', ['locale' => app()->getLocale(), 'idNews' => $this->idNews])->with('warning', Lang::get('News operation cancelled'));
    }

    public function edit($idNews)
    {
        $news = News::findOrFail($idNews);
        $this->idNews = $idNews;
        $this->title = $news->title;
        $this->enabled = $news->enabled == 1 ? true : false;
        $this->content = $news->content;
        $this->published_at = $news->published_at;
        $this->update = true;
        $this->selectedHashtags = $news->hashtags()->pluck('hashtags.id')->toArray();
    }

    public function updateNews()
    {
        $this->validate();
        try {
            $params = [
                'title' => $this->title,
                'enabled' => $this->enabled == 1 ? true : false,
                'content' => $this->content
            ];
            if ($this->enabled == 1 && is_null($this->published_at)) {
                $params['published_at'] = now();
            }
            $news = News::where('id', $this->idNews)->first();
            $news->update($params);
            $news->hashtags()->sync($this->selectedHashtags);
            if ($this->mainImage) {
                if (!is_null($news->mainImage)) {
                    Storage::disk('public2')->delete($news->mainImage->url);
                }
                $imagePath = $this->mainImage->store('news/' . News::IMAGE_TYPE_MAIN, 'public2');
                $news->mainImage()->delete();
                $news->mainImage()->create([
                    'url' => $imagePath,
                    'type' => News::IMAGE_TYPE_MAIN,
                ]);
            }
        } catch (\Exception $exception) {
            $this->cancel();
            Log::error($exception->getMessage());
            return redirect()->route('news_index', ['locale' => app()->getLocale(), 'idNews' => $this->idNews])->with('danger', Lang::get('Something goes wrong while updating News'));
        }
        return redirect()->route('news_index', ['locale' => app()->getLocale(), 'idNews' => $this->idNews])->with('success', Lang::get('News Updated Successfully'));

    }

    public function store()
    {
        $this->validate();
        $news = [
            'title' => $this->title,
            'enabled' => $this->enabled == 1 ? true : false,
            'content' => $this->content
        ];
        try {
            $createdNews = News::create($news);
            $createdNews->hashtags()->sync($this->selectedHashtags);
            $translations = ['title', 'content'];
            foreach ($translations as $translation) {
                TranslaleModel::create(
                    [
                        'name' => TranslaleModel::getTranslateName($createdNews, $translation),
                        'value' => $this->{$translation} . ' AR',
                        'valueFr' => $this->{$translation} . ' FR',
                        'valueEn' => $this->{$translation} . ' EN',
                        'valueTr' => $this->{$translation} . ' TR',
                        'valueEs' => $this->{$translation} . ' ES',
                        'valueRu' => $this->{$translation} . ' Ru',
                        'valueDe' => $this->{$translation} . ' De',
                    ]);
            }
            if ($this->mainImage) {
                $imagePath = $this->mainImage->store('news/' . News::IMAGE_TYPE_MAIN, 'public2');
                $createdNews->mainImage()->create([
                    'url' => $imagePath,
                    'type' => News::IMAGE_TYPE_MAIN,
                ]);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('news_index', ['locale' => app()->getLocale(), 'idNews' => $this->idNews])->with('danger', Lang::get('Something goes wrong while creating News'));
        }
        return redirect()->route('news_index', ['locale' => app()->getLocale(), 'idNews' => $this->idNews])->with('success', Lang::get('News Created Successfully'));
    }

    public function render()
    {
        $params = ['news' => News::find($this->idNews)];
        return view('livewire.news-create-update', $params)->extends('layouts.master')->section('content');
    }
}
