<?php

namespace App\Services;

use App\Models\identificationuserrequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class IdentificationUserRequestService
{
    /**
     * Create identification user request
     *
     * @param string $idUser User's business ID
     * @param int $status New status value
     * @return array Result array with success status and message
     */
    public function createIdentificationRequest(string $idUser, int $status): array
    {
        try {
            $request = identificationuserrequest::create([
                'idUser' => $idUser,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'response' => 0,
                'note' => '',
                'status' => $status
            ]);

            return [
                'success' => true,
                'message' => 'Identification request created successfully',
                'request' => $request
            ];

        } catch (\Exception $exception) {
            Log::error('Error creating identification request: ' . $exception->getMessage(), [
                'idUser' => $idUser,
                'status' => $status
            ]);

            return [
                'success' => false,
                'message' => 'Error creating identification request'
            ];
        }
    }

    /**
     * Check if user has an identification request
     *
     * @param string $idUser User's business ID
     * @return bool
     */
    public function hasIdentificationRequest(string $idUser): bool
    {
        try {
            return identificationuserrequest::where('idUser', $idUser)
                ->where('response', 0)
                ->exists();
        } catch (\Exception $exception) {
            Log::error('Error checking identification request: ' . $exception->getMessage(), [
                'idUser' => $idUser
            ]);
            return false;
        }
    }

    /**
     * Get latest rejected identification request
     *
     * @param string $idUser User's business ID
     * @param int $rejectedStatus Rejected status value
     * @return identificationuserrequest|null
     */
    public function getLatestRejectedRequest(string $idUser, int $rejectedStatus): ?identificationuserrequest
    {
        try {
            return identificationuserrequest::where('idUser', $idUser)
                ->where('status', $rejectedStatus)
                ->latest('responseDate')
                ->first();
        } catch (\Exception $exception) {
            Log::error('Error getting rejected identification request: ' . $exception->getMessage(), [
                'idUser' => $idUser
            ]);
            return null;
        }
    }
}
