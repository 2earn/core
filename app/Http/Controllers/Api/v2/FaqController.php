<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Faq\FaqService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    private FaqService $faqService;

    public function __construct(FaqService $faqService)
    {
        $this->faqService = $faqService;
    }

    /**
     * Get all FAQs
     */
    public function index()
    {
        $faqs = $this->faqService->getAll();
        return response()->json(['status' => true, 'data' => $faqs]);
    }

    /**
     * Get paginated FAQs with search
     */
    public function getPaginated(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $faqs = $this->faqService->getPaginated(
            $request->input('search'),
            $request->input('per_page', 10)
        );

        return response()->json([
            'status' => true,
            'data' => $faqs->items(),
            'pagination' => [
                'current_page' => $faqs->currentPage(),
                'per_page' => $faqs->perPage(),
                'total' => $faqs->total(),
                'last_page' => $faqs->lastPage()
            ]
        ]);
    }

    /**
     * Get FAQ by ID
     */
    public function show(int $id)
    {
        $faq = $this->faqService->getById($id);

        if (!$faq) {
            return response()->json(['status' => false, 'message' => 'FAQ not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $faq]);
    }

    /**
     * Create FAQ
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'answer' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $faq = $this->faqService->create($request->all());

        return response()->json([
            'status' => true,
            'data' => $faq,
            'message' => 'FAQ created successfully'
        ], 201);
    }

    /**
     * Update FAQ
     */
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'nullable|string',
            'answer' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $success = $this->faqService->update($id, $request->all());

            return response()->json([
                'status' => $success,
                'message' => $success ? 'FAQ updated successfully' : 'Update failed'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        }
    }

    /**
     * Delete FAQ
     */
    public function destroy(int $id)
    {
        try {
            $this->faqService->delete($id);
            return response()->json(['status' => true, 'message' => 'FAQ deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        }
    }
}

