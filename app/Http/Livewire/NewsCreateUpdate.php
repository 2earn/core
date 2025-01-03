<?php

namespace App\Http\Livewire;

use App\Models\Faq;
use App\Models\News;
use App\Models\TranslaleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class NewsCreateUpdate extends Component
{
    public $idNews;
    public $update;
    public $enabled = false;
    public $title, $content, $published_at;

    protected $rules = [
        'title' => 'required',
        'content' => 'required',
    ];

    public function mount(Request $request)
    {
        $this->idNews = $request->input('idNews');
        if (!is_null($this->idNews)) {
            $this->edit($this->idNews);
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
        $this->enabled = $news->enabled;
        $this->content = $news->content;
        $this->published_at = $news->published_at;
        $this->update = true;
    }

    public function update()
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
            News::where('id', $this->idNews)
                ->update($params);

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
            'enabled' => $this->enabled,
            'content' => $this->content
        ];
        try {
            $createdNews = News::create($news);
            $translations = ['title', 'content'];
            foreach ($translations as $translation) {
                TranslaleModel::create(
                    [
                        'name' => TranslaleModel::getTranslateName($createdNews, $translation),
                        'value' => $this->{$translation} . ' AR',
                        'valueFr' => $this->{$translation} . ' FR',
                        'valueEn' => $this->{$translation} . ' EN',
                        'valueTr' => $this->{$translation} . ' TR',
                        'valueEs' => $this->{$translation} . ' ES'
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
        $params = ['news' => Faq::find($this->idNews)];
        return view('livewire.news-create-update', $params)->extends('layouts.master')->section('content');
    }
}
