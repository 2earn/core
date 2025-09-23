<?php

namespace App\Livewire;

use App\Models\Hashtag;
use App\Models\TranslaleModel;
use Illuminate\Support\Str;
use Livewire\Component;

class HashtagCreateOrUpdate extends Component
{
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

    public function mount($id = null)
    {
        if ($id) {
            $hashtag = Hashtag::findOrFail($id);
            $this->hashtagId = $hashtag->id;
            $this->name = $hashtag->name;
            $this->slug = $hashtag->slug;
            $trans = TranslaleModel::where('name', TranslaleModel::getTranslateName($hashtag, 'name'))->first();
            if ($trans) {
                $this->translations = [
                    'ar' => $trans->value,
                    'fr' => $trans->valueFr,
                    'en' => $trans->valueEn,
                    'es' => $trans->valueEs,
                    'tr' => $trans->valueTr,
                    'ru' => $trans->valueRu,
                    'de' => $trans->valueDe,
                ];
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
            $hashtag = Hashtag::findOrFail($this->hashtagId);
            $hashtag->update([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);
        } else {
            $hashtag = Hashtag::create([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);
        }
        $trans = TranslaleModel::updateOrCreate(
            ['name' => TranslaleModel::getTranslateName($hashtag, 'name')],
            [
                'value' => !empty($this->translations['ar']) ? $this->translations['ar'] : $this->name . ' - ar',
                'valueFr' => !empty($this->translations['fr']) ? $this->translations['fr'] : $this->name . ' - fr',
                'valueEn' => !empty($this->translations['en']) ? $this->translations['en'] : $this->name . ' - en',
                'valueEs' => !empty($this->translations['es']) ? $this->translations['es'] : $this->name . ' - es',
                'valueTr' => !empty($this->translations['tr']) ? $this->translations['tr'] : $this->name . ' - tr',
                'valueRu' => !empty($this->translations['ru']) ? $this->translations['ru'] : $this->name . ' - ru',
                'valueDe' => !empty($this->translations['de']) ? $this->translations['de'] : $this->name . ' - de',
            ]
        );
        session()->flash('success', $this->hashtagId ? 'Hashtag updated successfully.' : 'Hashtag created successfully.');
        return redirect()->route('hashtags_index', app()->getLocale());
    }

    public function render()
    {
        return view('livewire.hashtag-create-or-update')->extends('layouts.master')->section('content');
    }
}
