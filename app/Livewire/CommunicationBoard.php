<?php

namespace App\Livewire;

use App\Models\News as NewsModel;
use App\Models\Survey;
use Core\Enum\StatusSurvey;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class CommunicationBoard extends Component
{
    public $communicationBoard = [];
    public $currentRouteName;

    public function mount()
    {
        $surveys = Survey::where('status', '<', StatusSurvey::ARCHIVED->value)->orderBy('id', 'desc')->get();
        $news = NewsModel::where('enabled', 1)->orderBy('id', 'desc')->get();
        $communicationBoard = $surveys->merge($news)->sortByDesc('created_at')->values();
        foreach ($communicationBoard as $key => $value) {
            if (get_class($value) == 'App\Models\Survey') {
                if ($value->canShow()) {
                    $this->communicationBoard[$key] = ['type' => get_class($value), 'value' => $value];
                }
            } else {
                $this->communicationBoard[$key] = ['type' => get_class($value), 'value' => $value];
            }
        }
        $this->currentRouteName = Route::currentRouteName();

    }

    public function render()
    {
        $params = [];
        return view('livewire.communication-board', $params)->extends('layouts.master')->section('content');
    }
}
