<?php

namespace App\Http\Controllers;

use App\Services\RepresentativesService;

class RepresentativesController extends Controller
{
    protected $representativesService;

    public function __construct(RepresentativesService $representativesService)
    {
        $this->representativesService = $representativesService;
    }

    public function index()
    {
        $representatives = $this->representativesService->getAll();
        return datatables($representatives)
            ->make(true);
    }


}
