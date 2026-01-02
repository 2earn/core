<?php
namespace  App\DAL;
use App\Enums\SettingsEnum;
use Core\Interfaces\ISettingsRepository;
use Illuminate\Support\Facades\DB;

class SettingsRepository implements  ISettingsRepository{
    public function getSetting(SettingsEnum $settings)
    {
       return DB::table('settings')
               ->where("idSETTINGS", "=", $settings)
               ->first();
    }
}
