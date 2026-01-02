<?php

namespace App\Livewire;


use App\Enums\LanguageEnum;
use Core\Services\settingsManager;
use Livewire\Component;

class CountriesManagement extends Component
{
    private settingsManager $settingsManager;
    public $discountBalance;
    protected $paginationTheme = 'bootstrap';
    public $allLanguage;
    public int $idL = -1;
    public $name;
    public $phonecode;
    public $langue;
    public $ISO;


    protected $listeners = ['initCountrie' => 'initCountrie'];

    public function mount(settingsManager $settingsManager)
    {
        $this->settingsManager = $settingsManager;
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
        $countrie = $settingsManager->getCountrieById($this->idL);
        if (!$countrie) return;
        $countrie->langage = $this->langue;
        $countrie->lang = LanguageEnum:: fromName($this->langue);
        $countrie->save();
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
