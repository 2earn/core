<?php

namespace Database\Seeders;

use App\Models\SurveyResponse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanUpDuplicateSurveyResponsesSeeder extends Seeder
{

    public function run(): void
    {
        $duplicates = DB::table('survey_responses')
            ->select('survey_id', 'user_id', DB::raw('COUNT(*) as total'))
            ->groupBy('survey_id', 'user_id')
            ->having('total', '>', 1)
            ->get();

        foreach ($duplicates as $dup) {
            Log::warning('survey_id : ' . $dup->survey_id . ' user_id : ' . $dup->user_id);
            $responses = SurveyResponse::where('survey_id', $dup->survey_id)
                ->where('user_id', $dup->user_id)
                ->orderBy('id')
                ->get();

            $responses->shift(); // removes and keeps the first one
            foreach ($responses as $extra) {
                $extra->delete();
            }
        }
    }
}
