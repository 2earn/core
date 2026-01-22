<?php

namespace App\Services;

use App\Enums\StatusRequest;
use App\Enums\TypeEventNotificationEnum;
use App\Enums\TypeNotificationEnum;
use App\Models\identificationuserrequest;
use App\Models\language;
use App\Models\MettaUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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

    /**
     * Get in-progress identification request by user ID
     *
     * @param string $idUser
     * @return identificationuserrequest|null
     */
    public function getInProgressRequestByUserId(string $idUser): ?identificationuserrequest
    {
        try {
            $request = identificationuserrequest::where('idUser', $idUser)
                ->where(function ($query) {
                    $query->where('status', '=', StatusRequest::InProgressNational->value)
                        ->orWhere('status', '=', StatusRequest::InProgressInternational->value)
                        ->orWhere('status', '=', StatusRequest::InProgressGlobal->value);
                })
                ->first();

            return $request;
        } catch (\Exception $e) {
            Log::error('Error fetching in-progress request by user ID: ' . $e->getMessage(), [
                'idUser' => $idUser
            ]);
            return null;
        }
    }

    /**
     * Update identification request
     *
     * @param identificationuserrequest $requestIdentification
     * @param int $status
     * @param int $response
     * @param string|null $note
     * @return bool
     */
    public function updateIdentity(identificationuserrequest $requestIdentification, int $status, int $response, ?string $note): bool
    {
        try {
            $requestIdentification->status = $status;
            $requestIdentification->idUserResponse = Auth::user()->idUser ?? null;
            $requestIdentification->response = $response;
            $requestIdentification->note = $note;
            $requestIdentification->responseDate = Carbon::now();
            return $requestIdentification->save();
        } catch (\Exception $e) {
            Log::error('Error updating identification request: ' . $e->getMessage(), [
                'requestId' => $requestIdentification->id ?? null
            ]);
            return false;
        }
    }

    /**
     * Reject identification request
     *
     * @param string $idUser
     * @param string $note
     * @param callable $notifyCallback Callback to handle notification
     * @return bool
     */
    public function rejectIdentity(string $idUser, string $note, callable $notifyCallback): bool
    {
        try {
            DB::beginTransaction();

            $requestIdentification = $this->getInProgressRequestByUserId($idUser);
            if (!$requestIdentification) {
                Log::warning('No in-progress identification request found for user', ['idUser' => $idUser]);
                return false;
            }

            $user = User::where('idUser', $idUser)->first();
            if (!$user) {
                Log::warning('User not found', ['idUser' => $idUser]);
                return false;
            }

            $userStatus = StatusRequest::OptValidated->value;
            if ($user->status == StatusRequest::InProgressInternational->value) {
                $userStatus = StatusRequest::ValidNational->value;
            }

            $this->updateIdentity($requestIdentification, $userStatus, 1, $note);

            User::where('idUser', $idUser)->update(['status' => $userStatus]);

            if ($user->iden_notif == 1) {
                $uMetta = MettaUser::where('idUser', $idUser)->first();
                $lang = app()->getLocale();

                if ($uMetta && $uMetta->idLanguage != null) {
                    $language = language::where('name', $uMetta->idLanguage)->first();
                    $lang = $language?->PrefixLanguage ?? $lang;
                }

                $notifyCallback($user->id, TypeEventNotificationEnum::RequestDenied, [
                    'msg' => $note,
                    'type' => TypeNotificationEnum::SMS,
                    'canSendSMS' => 1,
                    'lang' => $lang
                ]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting identity: ' . $e->getMessage(), [
                'idUser' => $idUser,
                'note' => $note
            ]);
            return false;
        }
    }

    /**
     * Validate identification request
     *
     * @param string $idUser
     * @param callable $getNewValidatedStatusCallback Callback to get new validated status
     * @param callable $notifyCallback Callback to handle notification
     * @return bool
     */
    public function validateIdentity(string $idUser, callable $getNewValidatedStatusCallback, callable $notifyCallback): bool
    {
        try {
            DB::beginTransaction();

            $requestIdentification = $this->getInProgressRequestByUserId($idUser);
            if (!$requestIdentification) {
                Log::warning('No in-progress identification request found for validation', ['idUser' => $idUser]);
                return false;
            }

            $user = User::where('idUser', $idUser)->first();
            if (!$user) {
                Log::warning('User not found for validation', ['idUser' => $idUser]);
                return false;
            }

            $newStatus = $getNewValidatedStatusCallback($idUser);
            if (!$newStatus) {
                Log::warning('Unable to determine new validated status', ['idUser' => $idUser]);
                return false;
            }

            $this->updateIdentity($requestIdentification, $newStatus, 1, null);

            User::where('idUser', $idUser)->update(['status' => $newStatus]);

            if ($user->iden_notif == 1) {
                $uMetta = MettaUser::where('idUser', $idUser)->first();
                $lang = app()->getLocale();

                if ($uMetta && $uMetta->idLanguage != null) {
                    $language = language::where('name', $uMetta->idLanguage)->first();
                    $lang = $language?->PrefixLanguage ?? $lang;
                }

                $notifyCallback($user->id, TypeEventNotificationEnum::RequestAccepted, [
                    'msg' => " ",
                    'type' => TypeNotificationEnum::SMS,
                    'canSendSMS' => 1,
                    'lang' => $lang
                ]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error validating identity: ' . $e->getMessage(), [
                'idUser' => $idUser
            ]);
            return false;
        }
    }
}

