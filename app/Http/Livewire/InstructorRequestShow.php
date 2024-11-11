<?php

namespace App\Http\Livewire;

use App\Models\InstructorRequest;
use App\Models\User;
use Core\Enum\BeInstructorRequestStatus;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class InstructorRequestShow extends Component
{
    public $rejectOpened = false;
    public $note;
    public $note_message;
    public $instructorRequest;

    public function mount()
    {
        $this->InstructorRequestId = Route::current()->parameter('id');
        $this->instructorRequest = InstructorRequest::find($this->InstructorRequestId);
        $this->userProfileImage = User::getUserProfileImage($this->instructorRequest->user->idUser);
    }

    public function validateRequest()
    {
        $instructorRequest = InstructorRequest::find($this->InstructorRequestId);
        $instructorRequest->update(
            [
                'status' => BeInstructorRequestStatus::Validated2earn->value,
                'examination_date' => now(),
                'examiner_id' => auth()->user()->id,
            ]);
        User::find($instructorRequest->user_id)->update(['instructor' => BeInstructorRequestStatus::Validated2earn->value]);

        return redirect()->route('requests_instructor', app()->getLocale())->with('success', trans('Instructor request is validated'));

    }

    public function initRejectRequest()
    {
        $this->rejectOpened = true;
    }

    public function rejectRequest()
    {
        if (!empty($this->note) && !is_null($this->note)) {
            $instructorRequest = InstructorRequest::find($this->InstructorRequestId);
            $instructorRequest->update(
                [
                    'status' => BeInstructorRequestStatus::Rejected->value,
                    'examination_date' => now(),
                    'note' => $this->note,
                    'examiner_id' => auth()->user()->id,
                ]
            );
            return redirect()->route('requests_instructor', app()->getLocale())->with('warning', trans('Instructor request is Rejected'));

        } else {
            $this->note_message = trans('Empty Rejection message');
        }
    }

    public function render()
    {
        $params = [
            'instructorRequests' => InstructorRequest::where('user_id', auth()->user()->id)->get(),
            'instructorRequest' => $this->instructorRequest
        ];
        return view('livewire.instructor-show', $params)->extends('layouts.master')->section('content');
    }
}
