<?php

namespace App\Livewire;

use App\Http\Traits\earnTrait;
use App\Services\UserContactService;
use App\Services\settingsManager;
use Livewire\Component;

class ContactNumber extends Component
{
    use earnTrait;

    public $search;

    protected $listeners = [
        'setActiveNumber' => 'setActiveNumber',
        'deleteContact' => 'deleteContact',
    ];

    public function deleteContact($id, settingsManager $settingsManager, UserContactService $userContactService)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) {
            return;
        }

        try {
            $userContactService->deleteContact($id);
            return redirect()->route('contact_number', app()->getLocale())->with('success', trans('Contact number deleted with success'));
        } catch (\Exception $e) {
            return redirect()->route('contact_number', app()->getLocale())->with('danger', trans($e->getMessage()));
        }
    }

    public function setActiveNumber($checked, $id, UserContactService $userContactService)
    {
        if (!$checked) {
            return;
        }

        $userContactService->setActiveNumber(auth()->user()->idUser, $id);
        return redirect()->route('contact_number', app()->getLocale())->with('success', trans('Number updated successfully'));
    }


    public function render(settingsManager $settingsManager, UserContactService $userContactService)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;

        $userContactNumber = $userContactService->getByUserIdWithSearch($userAuth->idUser, $this->search);

        return view('livewire.contact-number', ['userContactNumber' => $userContactNumber])->extends('layouts.master')->section('content');
    }
}
