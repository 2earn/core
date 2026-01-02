<?php

namespace App\Jobs;

use App\Models\translatetabs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class TranslationFilesToDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $paramsToUpdate = [];


    public function mergeFile($lang)
    {
        $pathFile = resource_path() . '/lang/' . $lang . '.json';
        $contents = File::get($pathFile);
        $json = collect(json_decode($contents));
        foreach ($json as $key => $value) {
            $keyLang = 'value' . ($lang == 'ar' ? '' : ucfirst($lang));
            if (!array_key_exists($key, $this->paramsToUpdate)) {
                $this->paramsToUpdate[$key]['name'] = is_null($value) ? '' : $value;
                $this->paramsToUpdate[$key][$keyLang] = is_null($value) ? '' : $value;
            }
            $this->paramsToUpdate[$key][$keyLang] = is_null($value) ? '' : $value;
        }
    }

    public function handle()
    {
        translatetabs::truncate();
        $pathFile = resource_path() . '/lang/en.json';
        $contents = File::get($pathFile);
        $json = collect(json_decode($contents));
        foreach ($json as $key => $value) {
            $this->paramsToUpdate[$key] = ['name' => $key, 'valueEn' => $value, 'value' => '', 'valueFr' => '', 'valueEs' => '', 'value' => '', 'valueEs' => '', 'valueTr' => '', 'valueRu' => '', 'valueDe' => ''];

        }
        $this->mergeFile('ar');
        $this->mergeFile('fr');
        $this->mergeFile('es');
        $this->mergeFile('tr');
        $this->mergeFile('ru');
        $this->mergeFile('de');
        $start_time = microtime(true);
        translatetabs::insert($this->paramsToUpdate);
        $end_time = microtime(true);
        Log::info('Translation update time: ' . ($end_time - $start_time));
    }
}
