<?php

namespace App\View\Components;

use App\Models\UserGuide;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class PageTitle extends Component
{


    public $bg;
    public $pageTitle;
    public $helpUrl;

    public function __construct($pageTitle)
    {
        $this->bg = "#464fed";

        if (Route::currentRouteName() == "user_balance_bfs") {
            $this->bg = '#bc34b6';
        } elseif (Route::currentRouteName() == "user_balance_db") {
            $this->bg = '#009fe3';
        }

        $this->pageTitle = $pageTitle;
        $currentRoute = Route::currentRouteName();
        $userGuide = UserGuide::whereJsonContains('routes', $currentRoute)->first();
        $this->helpUrl = $userGuide ? route('user_guides_show', ['id' => $userGuide->id, 'locale' => app()->getLocale()]) : '#';
    }

    public function render()
    {
        return view('components.page-title');
    }
}
