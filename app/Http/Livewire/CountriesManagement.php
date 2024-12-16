<?php

namespace App\Http\Livewire;


use Core\Enum\LanguageEnum;
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
        $countrie = $settingsManager->getCountrieById($id);
        if (!$countrie) return;
        $this->name = $countrie->name;
        $this->phonecode = $countrie->phonecode;
        $this->langue = $countrie->langage;
        $this->ISO = $countrie->apha2;
    }


    public function save(settingsManager $settingsManager)
    {
        $countrie = $settingsManager->getCountrieById($this->idL);
        if (!$countrie) return;
        $countrie->langage = $this->langue;
        $countrie->lang = LanguageEnum:: fromName($this->langue);
        $countrie->save();
        $this->dispatchBrowserEvent('toastr:info', ['message' => 'succes']);
        $this->dispatchBrowserEvent('close-modal');
        return redirect()->route('countries_management', app()->getLocale());
    }

    public function render(settingsManager $settingsManager)
    {
        $this->allLanguage = $settingsManager->getlanguages();
        return view('livewire.countries-management')->extends('layouts.master')->section('content');
    }
}
