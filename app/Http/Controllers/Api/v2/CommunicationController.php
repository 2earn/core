<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Communication\Communication;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CommunicationController extends Controller
{
    /**
     * Duplicate a survey
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function duplicateSurvey(int $id)
    {
        try {
            $duplicate = Communication::duplicateSurvey($id);

            if (!$duplicate) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to duplicate survey'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json([
                'status' => true,
                'message' => 'Survey duplicated successfully',
                'data' => $duplicate
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Survey not found or duplication failed',
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Duplicate news
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function duplicateNews(int $id)
    {
        try {
            $duplicate = Communication::duplicateNews($id);

            if (!$duplicate) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to duplicate news'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json([
                'status' => true,
                'message' => 'News duplicated successfully',
                'data' => $duplicate
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'News not found or duplication failed',
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Duplicate event
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function duplicateEvent(int $id)
    {
        try {
            $duplicate = Communication::duplicateEvent($id);

            if (!$duplicate) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to duplicate event'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json([
                'status' => true,
                'message' => 'Event duplicated successfully',
                'data' => $duplicate
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Event not found or duplication failed',
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }
}

