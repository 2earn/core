<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Partner\PartnerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PartnerController extends Controller
{
    private PartnerService $partnerService;

    public function __construct(PartnerService $partnerService)
    {
        $this->partnerService = $partnerService;
    }

    /**
     * Get all partners (non-paginated)
     */
    public function index()
    {
        try {
            $partners = $this->partnerService->getAllPartners();
            return response()->json(['status' => true, 'data' => $partners]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get filtered partners (paginated)
     */
    public function filtered(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $search = $request->input('search', '');
            $perPage = $request->input('per_page', 15);

            $partners = $this->partnerService->getFilteredPartners($search, $perPage);

            return response()->json([
                'status' => true,
                'data' => $partners->items(),
                'pagination' => [
                    'current_page' => $partners->currentPage(),
                    'per_page' => $partners->perPage(),
                    'total' => $partners->total(),
                    'last_page' => $partners->lastPage()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get a single partner by ID
     */
    public function show(int $id)
    {
        try {
            $partner = $this->partnerService->getPartnerById($id);

            if (!$partner) {
                return response()->json(['status' => false, 'message' => 'Partner not found'], 404);
            }

            return response()->json(['status' => true, 'data' => $partner]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Create a new partner
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'business_sector_id' => 'nullable|integer|exists:business_sectors,id',
            'platform_url' => 'required|url|max:255',
            'platform_description' => 'nullable|string',
            'partnership_reason' => 'nullable|string',
            'created_by' => 'nullable|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $partner = $this->partnerService->createPartner($request->all());

            if (!$partner) {
                return response()->json(['status' => false, 'message' => 'Failed to create partner'], 400);
            }

            return response()->json(['status' => true, 'data' => $partner], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update a partner
     */
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'nullable|string|max:255',
            'business_sector_id' => 'nullable|integer|exists:business_sectors,id',
            'platform_url' => 'nullable|url|max:255',
            'platform_description' => 'nullable|string',
            'partnership_reason' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $partner = $this->partnerService->updatePartner($id, $request->all());

            if (!$partner) {
                return response()->json(['status' => false, 'message' => 'Partner not found or update failed'], 404);
            }

            return response()->json(['status' => true, 'data' => $partner, 'message' => 'Partner updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a partner
     */
    public function destroy(int $id)
    {
        try {
            $result = $this->partnerService->deletePartner($id);

            if (!$result) {
                return response()->json(['status' => false, 'message' => 'Partner not found or delete failed'], 404);
            }

            return response()->json(['status' => true, 'message' => 'Partner deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get partners by business sector
     */
    public function byBusinessSector(Request $request, int $businessSectorId)
    {
        try {
            $partners = $this->partnerService->getPartnersByBusinessSector($businessSectorId);
            return response()->json(['status' => true, 'data' => $partners]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Search partners by company name
     */
    public function searchByCompanyName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $partners = $this->partnerService->searchPartnersByCompanyName($request->input('company_name'));
            return response()->json(['status' => true, 'data' => $partners]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

