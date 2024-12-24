<?php

namespace App\Http\Livewire;

use Core\Enum\StatusRequest;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class IdentificationRequest extends Component
{
    public function render()
    {
        $identificationRequests = DB::table('identificationuserrequest as ir')
            ->select(
                'u1.id as id',
                'u1.idUser as idUser',
                'u1.status as status',
                DB::raw("CONCAT(mu.enFirstName, ' ', mu.enLastName) as enName"),
                'mu.nationalID as nationalID', 'u1.fullphone_number', 'u1.internationalID',
                'u1.expiryDate', 'ir.created_at as DateCreation', 'ir.id as irid',
                'u2.name as Validator', 'ir.response', 'ir.responseDate as DateReponce',
                'ir.note'
            )
            ->join('users as u1', 'ir.IdUser', '=', 'u1.idUser')
            ->join('metta_users as mu', 'ir.idUser', '=', 'mu.idUser')
            ->leftJoin('users as u2', 'ir.idUserResponse', '=', 'u2.idUser')
            ->where(function ($query) {
                $query->where('ir.status', StatusRequest::InProgressNational->value)
                    ->orWhere('ir.status', StatusRequest::InProgressInternational->value)
                    ->orWhere('ir.status', StatusRequest::InProgressGlobal->value);
            })->get();
        return view('livewire.identification-request', ['identificationRequests' => $identificationRequests])->extends('layouts.master')->section('content');
    }
}
