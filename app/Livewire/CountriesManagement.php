<?php

namespace App\Livewire;


use App\Enums\LanguageEnum;
use App\Services\CountryService;
use App\Services\settingsManager;
use Livewire\Component;

class CountriesManagement extends Component
{
    private settingsManager $settingsManager;
    private CountryService $countryService;
    public $discountBalance;
    protected $paginationTheme = 'bootstrap';
    public $allLanguage;
    public int $idL = -1;
    public $name;
    public $phonecode;
    public $langue;
    public $ISO;


    protected $listeners = ['initCountrie' => 'initCountrie'];

    public function mount(settingsManager $settingsManager, CountryService $countryService)
    {
        $this->settingsManager = $settingsManager;
        $this->countryService = $countryService;
    }

    public function initCountrie($id, settingsManager $settingsManager)
    {
        $this->idL = $id;
        $countries = $settingsManager->getCountrieById($id);
        if (!$countries) return;
        $this->name = $countries->name;
        $this->phonecode = $countries->phonecode;
        $this->langue = $countries->langage;
        $this->ISO = $countries->apha2;
    }


    public function save(settingsManager $settingsManager)
    {
        // Update country language using service
        $result = $this->countryService->updateCountryLanguage($this->idL, $this->langue);

        if (!$result['success']) {
            $this->dispatch('toastr:error', ['message' => $result['message']]);
            return;
        }

        $this->dispatch('toastr:info', ['message' => 'succes']);
        $this->dispatch('close-modal');
        return redirect()->route('countries_management', app()->getLocale());
    }

    public function render(settingsManager $settingsManager)
    {
        $this->allLanguage = $settingsManager->getlanguages();
        return view('livewire.countries-management')->extends('layouts.master')->section('content');
    }
}
