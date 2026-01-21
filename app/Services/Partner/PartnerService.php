<?php

namespace App\Services\Partner;

use App\Models\Partner;
use Illuminate\Support\Facades\Log;

class PartnerService
{
    /**
     * Get all partners
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPartners()
    {
        try {
            return Partner::orderBy('created_at', 'DESC')->get();
        } catch (\Exception $e) {
            Log::error('Error fetching all partners: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get partner by ID
     *
     * @param int $id
     * @return Partner|null
     */
    public function getPartnerById(int $id): ?Partner
    {
        try {
            return Partner::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching partner by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a new partner
     *
     * @param array $data
     * @return Partner|null
     */
    public function createPartner(array $data): ?Partner
    {
        try {
            return Partner::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating partner: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update partner
     *
     * @param int $id
     * @param array $data
     * @return Partner|null
     */
    public function updatePartner(int $id, array $data): ?Partner
    {
        try {
            $partner = Partner::find($id);
            if ($partner) {
                $partner->update($data);
                return $partner;
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Error updating partner: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete partner
     *
     * @param int $id
     * @return bool
     */
    public function deletePartner(int $id): bool
    {
        try {
            $partner = Partner::find($id);
            if ($partner) {
                return $partner->delete();
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Error deleting partner: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get filtered and paginated partners
     *
     * @param string $searchTerm
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getFilteredPartners(string $searchTerm = '', int $perPage = 15)
    {
        try {
            $query = Partner::with('businessSector')->orderBy('created_at', 'DESC');

            if (!empty($searchTerm)) {
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('company_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('platform_url', 'like', '%' . $searchTerm . '%')
                        ->orWhereHas('businessSector', function ($q) use ($searchTerm) {
                            $q->where('name', 'like', '%' . $searchTerm . '%');
                        });
                });
            }

            return $query->paginate($perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching filtered partners: ' . $e->getMessage());
            return Partner::paginate($perPage);
        }
    }

    /**
     * Get partners by business sector
     *
     * @param int $businessSectorId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPartnersByBusinessSector(int $businessSectorId)
    {
        try {
            return Partner::where('business_sector_id', $businessSectorId)
                ->with('businessSector')
                ->orderBy('created_at', 'DESC')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching partners by business sector: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Search partners by company name
     *
     * @param string $companyName
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchPartnersByCompanyName(string $companyName)
    {
        try {
            return Partner::where('company_name', 'like', '%' . $companyName . '%')
                ->orderBy('created_at', 'DESC')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error searching partners by company name: ' . $e->getMessage());
            return collect();
        }
    }
}

