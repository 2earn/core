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

class TranslationFilesToDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
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
                    translatetabs::create($params);
                } else {
                    translatetabs::where('name', $key)->update(['valueEn' => $value]);
                }
            }
        }

        $this->mergeFile('ar', 'value');
        $this->mergeFile('fr', 'valueFr');
        $this->mergeFile('es', 'valueEs');
        $this->mergeFile('tr', 'valueTr');
    }

    public function mergeFile($lang, $field)
    {

        $pathFile = resource_path() . '/lang/' . $lang . '.json';
        $contents = File::get($pathFile);
        $json = collect(json_decode($contents));
        foreach ($json as $key => $value) {
            translatetabs::where('name', $key)->update([$field => $value]);
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
