<?php

namespace App\Services;

use Core\Enum\StatusRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IdentificationRequestService
{
    /**
     * Get all in-progress identification requests
     *
     * @return Collection
     */
    public function getInProgressRequests(): Collection
    {
        try {
            return DB::table('identificationuserrequest as ir')
                ->select(
                    'u1.id as id',
                    'u1.idUser as idUser',
                    'u1.status as status',
                    DB::raw("CONCAT(mu.enFirstName, ' ', mu.enLastName) as enName"),
                    'mu.nationalID as nationalID',
                    'u1.fullphone_number',
                    'u1.internationalID',
                    'u1.expiryDate',
                    'ir.created_at as DateCreation',
                    'ir.id as irid',
                    'u2.name as Validator',
                    'ir.response',
                    'ir.responseDate as DateReponce',
                    'ir.note'
                )
                ->join('users as u1', 'ir.IdUser', '=', 'u1.idUser')
                ->join('metta_users as mu', 'ir.idUser', '=', 'mu.idUser')
                ->leftJoin('users as u2', 'ir.idUserResponse', '=', 'u2.idUser')
                ->where(function ($query) {
                    $query->where('ir.status', StatusRequest::InProgressNational->value)
                        ->orWhere('ir.status', StatusRequest::InProgressInternational->value)
                        ->orWhere('ir.status', StatusRequest::InProgressGlobal->value);
                })
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching in-progress identification requests: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get identification requests by status
     *
     * @param array $statuses
     * @return Collection
     */
    public function getRequestsByStatus(array $statuses): Collection
    {
        try {
            return DB::table('identificationuserrequest as ir')
                ->select(
                    'u1.id as id',
                    'u1.idUser as idUser',
                    'u1.status as status',
                    DB::raw("CONCAT(mu.enFirstName, ' ', mu.enLastName) as enName"),
                    'mu.nationalID as nationalID',
                    'u1.fullphone_number',
                    'u1.internationalID',
                    'u1.expiryDate',
                    'ir.created_at as DateCreation',
                    'ir.id as irid',
                    'u2.name as Validator',
                    'ir.response',
                    'ir.responseDate as DateReponce',
                    'ir.note'
                )
                ->join('users as u1', 'ir.IdUser', '=', 'u1.idUser')
                ->join('metta_users as mu', 'ir.idUser', '=', 'mu.idUser')
                ->leftJoin('users as u2', 'ir.idUserResponse', '=', 'u2.idUser')
                ->whereIn('ir.status', $statuses)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching identification requests by status: ' . $e->getMessage(), [
                'statuses' => $statuses
            ]);
            return collect();
        }
    }

    /**
     * Get identification request by ID
     *
     * @param int $id
     * @return object|null
     */
    public function getById(int $id): ?object
    {
        try {
            return DB::table('identificationuserrequest as ir')
                ->select(
                    'u1.id as id',
                    'u1.idUser as idUser',
                    'u1.status as status',
                    DB::raw("CONCAT(mu.enFirstName, ' ', mu.enLastName) as enName"),
                    'mu.nationalID as nationalID',
                    'u1.fullphone_number',
                    'u1.internationalID',
                    'u1.expiryDate',
                    'ir.created_at as DateCreation',
                    'ir.id as irid',
                    'u2.name as Validator',
                    'ir.response',
                    'ir.responseDate as DateReponce',
                    'ir.note'
                )
                ->join('users as u1', 'ir.IdUser', '=', 'u1.idUser')
                ->join('metta_users as mu', 'ir.idUser', '=', 'mu.idUser')
                ->leftJoin('users as u2', 'ir.idUserResponse', '=', 'u2.idUser')
                ->where('ir.id', $id)
                ->first();
        } catch (\Exception $e) {
            Log::error('Error fetching identification request by ID: ' . $e->getMessage(), ['id' => $id]);
            return null;
        }
    }
}

