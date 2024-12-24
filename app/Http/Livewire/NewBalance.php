<?php

namespace App\Http\Livewire;

use App\Jobs\TranslationFilesToDatabase;
use Livewire\Component;

class NewBalance extends Component
{
    public function render()
    {
        $start_time = microtime(true);
        $job= new TranslationFilesToDatabase();
        $job->handle();
        $end_time = microtime(true);
        $execution_time = ($end_time - $start_time);
        dd("Script Execution Time = " . $execution_time . " sec");

        return view('livewire.new-balance')->extends('layouts.master')->section('content');
    }
}
