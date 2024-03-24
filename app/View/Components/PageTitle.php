<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PageTitle extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $bg ;
    public $pageTitle ;
    public function __construct($bg=null,$pageTitle)
    {
        $this->bg=$bg ;
        $this->pageTitle = $pageTitle;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.page-title');
    }
}
