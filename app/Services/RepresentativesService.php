<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RepresentativesService
{
    /**
     * Get all representatives
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll(): \Illuminate\Support\Collection
    {
        try {
            return DB::table('representatives')->get();
        } catch (\Exception $e) {
            Log::error('Error fetching representatives: ' . $e->getMessage());
            return collect([]);
        }
    }
}

