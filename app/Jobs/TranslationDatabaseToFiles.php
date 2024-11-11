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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $all = translatetabs::all();
        foreach ($all as $key => $value) {
            $this->tabfin[$value->name] = $value->value;
            $this->tabfinFr[$value->name] = $value->valueFr;
            $this->tabfinEn[$value->name] = $value->valueEn;
            $this->tabfinTr[$value->name] = $value->valueTr;
            $this->tabfinEs[$value->name] = $value->valueEs;
        }
        $pathFile = resource_path() . '/lang/ar.json';
        $pathFileFr = resource_path() . '/lang/fr.json';
        $pathFileEn = resource_path() . '/lang/en.json';
        $pathFileTr = resource_path() . '/lang/tr.json';
        $pathFileEs = resource_path() . '/lang/es.json';

        File::put($pathFile, json_encode($this->tabfin, JSON_UNESCAPED_UNICODE));
        File::put($pathFileFr, json_encode($this->tabfinFr, JSON_UNESCAPED_UNICODE));
        File::put($pathFileEn, json_encode($this->tabfinEn, JSON_UNESCAPED_UNICODE));
        File::put($pathFileTr, json_encode($this->tabfinTr, JSON_UNESCAPED_UNICODE));
        File::put($pathFileEs, json_encode($this->tabfinEs, JSON_UNESCAPED_UNICODE));
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
