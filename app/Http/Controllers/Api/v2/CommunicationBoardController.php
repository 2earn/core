<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\CommunicationBoardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommunicationBoardController extends Controller
{
    private CommunicationBoardService $communicationBoardService;

    public function __construct(CommunicationBoardService $communicationBoardService)
    {
        $this->communicationBoardService = $communicationBoardService;
    }

    /**
     * Get all communication board items (surveys, news, events)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $items = $this->communicationBoardService->getCommunicationBoardItems();

            return response()->json([
                'status' => true,
                'data' => $items
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching communication board items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all communication board items (alias for index)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        return $this->index();
    }
}

