<?php

namespace App\Livewire;

use App\Services\Hashtag\HashtagService;
use App\Services\TranslaleModelService;
use Illuminate\Support\Str;
use Livewire\Component;

class HashtagCreateOrUpdate extends Component
{
    protected HashtagService $hashtagService;
    protected TranslaleModelService $translationService;

    public $hashtagId;
    public $name = '';
    public $slug = '';
    public $translations = [
        'ar' => '', 'fr' => '', 'en' => '', 'es' => '', 'tr' => '', 'ru' => '', 'de' => ''
    ];

    protected function rules()
    {
        $id = $this->hashtagId ?? 'NULL';
        return [
            'name' => 'required|string|max:255|unique:hashtags,name,' . $id,
            'slug' => 'required|string|max:255|unique:hashtags,slug,' . $id,
        ];
    }

    public function boot(HashtagService $hashtagService, TranslaleModelService $translationService)
    {
        $this->hashtagService = $hashtagService;
        $this->translationService = $translationService;
    }

    public function mount($id = null)
    {
        if ($id) {
            $hashtag = $this->hashtagService->findByIdOrFail($id);
            $this->hashtagId = $hashtag->id;
            $this->name = $hashtag->name;
            $this->slug = $hashtag->slug;

            $translateName = $this->translationService->getTranslateName($hashtag, 'name');
            $trans = $this->translationService->getByName($translateName);

            if ($trans) {
                $this->translations = $this->translationService->getTranslationsArray($trans);
            }
        }
    }

    public function updatedName()
    {
        $this->slug = Str::slug($this->name);
    }

    public function save()
    {
        $this->validate();

        if ($this->hashtagId) {
            $success = $this->hashtagService->update($this->hashtagId, [
                'name' => $this->name,
                'slug' => $this->slug,
            ]);

            if (!$success) {
                session()->flash('error', 'Hashtag update failed.');
                return redirect()->route('hashtags_index', app()->getLocale());
            }

            $hashtag = $this->hashtagService->findByIdOrFail($this->hashtagId);
        } else {
            $hashtag = $this->hashtagService->create([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);

            if (!$hashtag) {
                session()->flash('error', 'Hashtag creation failed.');
                return redirect()->route('hashtags_index', app()->getLocale());
            }
        }

        $translateName = $this->translationService->getTranslateName($hashtag, 'name');
        $preparedTranslations = $this->translationService->prepareTranslationsWithFallback(
            $this->translations,
            $this->name
        );

        $this->translationService->updateOrCreate($translateName, $preparedTranslations);

        session()->flash('success', $this->hashtagId ? 'Hashtag updated successfully.' : 'Hashtag created successfully.');
        return redirect()->route('hashtags_index', app()->getLocale());
    }

    public function render()
    {
        return view('livewire.hashtag-create-or-update')->extends('layouts.master')->section('content');
    }
}
