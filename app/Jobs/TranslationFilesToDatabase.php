<?php

namespace App\Jobs;

use Core\Models\translatetabs;
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

    public function __construct()
    {

    }

    public function mergeFile($lang, $field)
    {
        $pathFile = resource_path() . '/lang/' . $lang . '.json';
        $contents = File::get($pathFile);
        $json = collect(json_decode($contents));
        foreach ($json as $key => $value) {
            $keyLang = 'value' . ($lang == 'ar' ? '' : ucfirst($lang));
            $this->paramsToUpdate[$key]['name'] = is_null($value) ? '' : $value;
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
            if ($value) {
                if (!translatetabs::where(DB::raw('BINARY `name`'), $key)->exists()) {
                    $params = [
                        'name' => $key,
                        'valueEn' => $value,
                        'value' => '',
                        'valueFr' => '',
                        'valueEs' => '',
                        'valueTr' => '',
                    ];
                    $this->paramsToUpdate[$key] = $params;
                } else {
                    $this->paramsToUpdate['name'] = ['valueEn' => $value];
                }
            }
        }

        $this->mergeFile('ar', 'value');
        $this->mergeFile('fr', 'valueFr');
        $this->mergeFile('es', 'valueEs');
        $this->mergeFile('tr', 'valueTr');

        $start_time = microtime(true);
        translatetabs::insert($this->paramsToUpdate);
        $end_time = microtime(true);
        Log::info('Translation update time: ' . ($end_time - $start_time));
    }
}
