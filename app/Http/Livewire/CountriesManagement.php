<?php

namespace App\Http\Livewire;


use Core\Enum\LanguageEnum;
use Core\Models\countrie;
use Core\Services\settingsManager;
use Core\ViewModel\CountrieViewModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
//use App\Traits\Livewire\GuardsAgainstAccess;

class CountriesManagement extends Component
{
//    use GuardsAgainstAccess;
    private settingsManager $settingsManager;
    public $discountBalance;
    protected $paginationTheme = 'bootstrap';
    public $allLanguage;

    public int $idL = -1 ;
    public $name ;
    public $phonecode ;
    public $langue ;
    public  $ISO ;


    protected $listeners = [
        'initCountrie' => 'initCountrie'
    ];

    public  function  mount(
        settingsManager $settingsManager
    )
    {
        $this->settingsManager = $settingsManager;

    }
//    protected $guard = 'admin';


    public function initCountrie($id, settingsManager $settingsManager)
    {
        $this->idL = $id;
        $countrie = $settingsManager->getCountrieById($id);
        if (!$countrie) return;
        $this->name = $countrie->name ;
        $this->phonecode=$countrie->phonecode;
        $this->langue = $countrie->langage ;
        $this->ISO = $countrie->apha2 ;
     }
    public function render(settingsManager $settingsManager)
    {
//        DB::table('countries')
////            ->where('id','=','1')
//            ->paginate(100);
//        require( 'ssp.class.php' );
       // $countries = $this->settingsManager->getAllCountries();
//        dd(json_encode($countries));
        $this->allLanguage = $settingsManager->getlanguages();

        return view('livewire.countries-management')->extends('layouts.master')->section('content');
    }
    public function save(settingsManager $settingsManager)
    {
        $countrie = $settingsManager->getCountrieById($this->idL);
        if(!$countrie) return;
        $countrie->langage = $this->langue;
        $countrie->lang = LanguageEnum:: fromName($this->langue) ;
        $countrie->save();
        $this->dispatchBrowserEvent('toastr:info',[
            'message'=>'succes'
        ]);
        $this->dispatchBrowserEvent('close-modal');

          return redirect()->route('countries_management', app()->getLocale());
    }
}
