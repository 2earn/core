<?php

namespace App\Http\Livewire;

use Core\Enum\StatusRequest;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class IdentificationRequest extends Component
{
    public function render()
    {
        $identificationRequests = DB::select("SELECT  u1.id id,u1.idUser idUser, u1.status status, concat(mu.enFirstName,' ', mu.enLastName)  enName , mu.nationalID  nationalID , u1.fullphone_number, u1.internationalID, u1.expiryDate, ir.created_at DateCreation, ir.id irid, u2.name Validator, ir.response, ir.responseDate DateReponce , ir.note from identificationuserrequest ir
inner join users u1 on ir.IdUser = u1.idUser inner join metta_users mu on ir.idUser = mu.idUser left join users u2 on ir.idUserResponse = u2.idUser where (ir.status = ? or ir.status = ? or ir.status = ?)", [StatusRequest::InProgressNational->value, StatusRequest::InProgressInternational->value, StatusRequest::InProgressGlobal->value]);
        return view('livewire.identification-request', ['identificationRequests' => $identificationRequests])->extends('layouts.master')->section('content');
    }
}
