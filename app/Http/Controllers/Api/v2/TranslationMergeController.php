<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Translation\TranslationMergeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TranslationMergeController extends Controller
{
    protected TranslationMergeService $translationMergeService;

    public function __construct(TranslationMergeService $translationMergeService)
    {
        $this->translationMergeService = $translationMergeService;
    }

    /**
     * Merge translations from source file into target language file
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function merge(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'source_path' => 'required|string',
                'language_code' => 'required|string|in:ar,fr,en,es,tr,de,ru'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $result = $this->translationMergeService->mergeTranslations(
                $request->source_path,
                $request->language_code
            );

            $status = $result['success'] ? 200 : 400;

            return response()->json($result, $status);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during merge: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Merge translations using default source path
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function mergeDefault(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'language_code' => 'required|string|in:ar,fr,en,es,tr,de,ru'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $sourcePath = $this->translationMergeService->getDefaultSourcePath($request->language_code);

            $result = $this->translationMergeService->mergeTranslations(
                $sourcePath,
                $request->language_code
            );

            $status = $result['success'] ? 200 : 400;

            return response()->json($result, $status);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during merge: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get language name from language code
     *
     * @param string $code
     * @return JsonResponse
     */
    public function getLanguageName(string $code): JsonResponse
    {
        try {
            $name = $this->translationMergeService->getLanguageName($code);

            return response()->json([
                'success' => true,
                'language_code' => $code,
                'language_name' => $name
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching language name: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get default source path for a language
     *
     * @param string $code
     * @return JsonResponse
     */
    public function getDefaultSourcePath(string $code): JsonResponse
    {
        try {
            $path = $this->translationMergeService->getDefaultSourcePath($code);

            return response()->json([
                'success' => true,
                'language_code' => $code,
                'default_source_path' => $path,
                'exists' => file_exists($path)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching source path: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get list of supported languages
     *
     * @return JsonResponse
     */
    public function supportedLanguages(): JsonResponse
    {
        try {
            $languages = [
                ['code' => 'ar', 'name' => 'Arabic'],
                ['code' => 'fr', 'name' => 'French'],
                ['code' => 'en', 'name' => 'English'],
                ['code' => 'es', 'name' => 'Spanish'],
                ['code' => 'tr', 'name' => 'Turkish'],
                ['code' => 'de', 'name' => 'German'],
                ['code' => 'ru', 'name' => 'Russian'],
            ];

            return response()->json([
                'success' => true,
                'data' => $languages
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching supported languages: ' . $e->getMessage()
            ], 500);
        }
    }
}

