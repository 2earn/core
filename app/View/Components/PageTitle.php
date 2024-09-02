<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class PageTitle extends Component
{


    public $bg;
    public $pageTitle;

    public function __construct($pageTitle)
    {
        $this->bg = "#464fed";

        if (Route::currentRouteName() == "user_balance_bfs") {
            $this->bg = '#bc34b6';
        } elseif (Route::currentRouteName() == "user_balance_db") {
            $this->bg = '#009fe3';
        }

        $this->pageTitle = $pageTitle;
    }

    public function render()
    {
        return view('components.page-title');
    }
}
