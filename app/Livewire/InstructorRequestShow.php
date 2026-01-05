<?php

namespace App\Livewire;

use App\Enums\BeInstructorRequestStatus;
use App\Services\InstructorRequestService;
use App\Services\UserService;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class InstructorRequestShow extends Component
{
    protected InstructorRequestService $instructorRequestService;
    protected UserService $userService;

    public $rejectOpened = false;
    public $note;
    public $note_message;
    public $instructorRequest;
    public $InstructorRequestId;
    public $userProfileImage;

    public function boot(InstructorRequestService $instructorRequestService, UserService $userService)
    {
        $this->instructorRequestService = $instructorRequestService;
        $this->userService = $userService;
    }

    public function mount()
    {
        $this->InstructorRequestId = Route::current()->parameter('id');
        $this->instructorRequest = $this->instructorRequestService->getById($this->InstructorRequestId);

        if ($this->instructorRequest && $this->instructorRequest->user) {
            $this->userProfileImage = $this->userService->getUserProfileImage($this->instructorRequest->user->idUser);
        }
    }

    public function validateRequest()
    {
        $success = $this->instructorRequestService->updateStatus(
            $this->InstructorRequestId,
            BeInstructorRequestStatus::Validated2earn->value
        );

        if (!$success) {
            return redirect()->route('requests_instructor', app()->getLocale())
                ->with('danger', trans('Failed to validate instructor request'));
        }

        $instructorRequest = $this->instructorRequestService->getById($this->InstructorRequestId);

        if ($instructorRequest) {
            $this->userService->update($instructorRequest->user_id, [
                'instructor' => BeInstructorRequestStatus::Validated2earn->value
            ]);
        }

        return redirect()->route('requests_instructor', app()->getLocale())
            ->with('success', trans('Instructor request is validated'));
    }

    public function initRejectRequest()
    {
        $this->rejectOpened = true;
    }

    public function rejectRequest()
    {
        if (!empty($this->note) && !is_null($this->note)) {
            $success = $this->instructorRequestService->updateStatusWithNote(
                $this->InstructorRequestId,
                BeInstructorRequestStatus::Rejected->value,
                $this->note
            );

            if ($success) {
                return redirect()->route('requests_instructor', app()->getLocale())
                    ->with('warning', trans('Instructor request is Rejected'));
            } else {
                $this->note_message = trans('Failed to reject request');
            }
        } else {
            $this->note_message = trans('Empty Rejection message');
        }
    }

    public function render()
    {
        $instructorRequests = $this->instructorRequestService->getByUserId(auth()->user()->id);

        $params = [
            'instructorRequests' => $instructorRequests,
            'instructorRequest' => $this->instructorRequest
        ];

        return view('livewire.instructor-show', $params)
            ->extends('layouts.master')
            ->section('content');
    }
}
