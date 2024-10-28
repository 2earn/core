<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StaticNews extends Component
{
    public $enableStaticNews = 0;

    public function render()
    {
        $param = DB::table('settings')->where("ParameterName", "=", "ENABLE_STATIC_NEWS")->first();

        if (!is_null($param)) {
            $this->enableStaticNews = $param->IntegerValue;
        }
            return view('livewire.static-news');

    }
}
