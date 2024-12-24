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

    private $paramsToAdd = [];
    private $paramsToUpdate = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
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
            $this->paramsToUpdate[$key][$keyLang] = $value;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
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
                    $this->paramsToAdd[$key] = $params;
                } else {
                    $this->paramsToUpdate['name'] = ['valueEn' => $value];
                }
            }
        }


        $this->mergeFile('ar', 'value');
        $this->mergeFile('fr', 'valueFr');
        $this->mergeFile('es', 'valueEs');
        $this->mergeFile('tr', 'valueTr');

        foreach ($this->paramsToAdd as $item) {
            translatetabs::create($item);
        }
        foreach ($this->paramsToUpdate as $key => $item) {
            if (translatetabs::where('name', $key)->exists()) {
                DB::table('translatetab')->where('name', $key)
                    ->update(
                        [
                            'value' => $item['value'],
                            'valueFr' => $item['valueFr'],
                            'valueEs' => isset($item['valueEs']) ? $item['valueEs'] : '',
                            'valueTr' => isset($item['valueTr']) ? $item['valueTr'] : ''
                        ]
                    );
            }
        }
    }
}
