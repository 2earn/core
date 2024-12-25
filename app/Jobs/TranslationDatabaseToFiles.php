<?php

namespace App\Jobs;

use Core\Models\translatetabs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class TranslationDatabaseToFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $all;
    public function __construct()
    {
        $this->all = translatetabs::all();
    }


    public function languageToFile($lang, $index_key)
    {
        $mergedArray = $originalArray = [];
        $originalArray = array_map(function ($value) use ($index_key) {
            return [$value['name'] => $value[$index_key]];
        }, $this->all->toArray());

        foreach ($originalArray as $item) {
            foreach ($item as $key => $value) {
                $mergedArray[$key] = $value;
            }
        }
        File::put(resource_path() . '/lang/' . $lang . '.json', json_encode($mergedArray, JSON_UNESCAPED_UNICODE));
    }
    public function handle()
    {
        $langs = [
            ['ar', 'value'],
            ['fr', 'valueFr'],
            ['en', 'valueEn'],
            ['tr', 'valueTr'],
            ['es', 'valueEs'],
        ];
        foreach ($langs as $lang) {
            $this->languageToFile($lang[0], $lang[1]);
        }
    }
}
