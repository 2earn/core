<?php

namespace App\Livewire;

use Core\Enum\NotificationSettingEnum;
use Core\Enum\SettingsEnum;
use Core\Models\Setting;
use Core\Services\settingsManager;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NotificationSettings extends Component
{
    private settingsManager $settingsManager;
    public $setting_notif;
    public $nbrSms;
    public $nbrSmsPossible ;
    protected $rules = [
        'setting_notif.*.value' => 'required',
    ];

    public function mount(settingsManager $settingsManager)
    {
        $this->settingsManager = $settingsManager;
    }

    public function render(settingsManager $settingsManager)
    {
        $this->settingsManager = $settingsManager;
        $this->setting_notif = $this->settingsManager->getNotificationSetting($this->settingsManager->getAuthUser()->idUser);
        $this->nbrSms = $this->setting_notif->where('id', '=', NotificationSettingEnum::SMSByWeek->value)->first()->value;
        $this->nbrSmsPossible = Setting::where('idSETTINGS',SettingsEnum::NbrSmsPossible)->first()->DecimalValue;
        return view('livewire.notification-settings')->extends('layouts.master')->section('content');
    }

    public function save()
    {
        foreach ($this->setting_notif as $settig) {
            if ($settig['id'] == 19)
                $val = $this->nbrSms;
            else
                $val = $settig["value"];
            DB::table('user_notification_setting')
                ->where('idNotification', $settig["idNotification"])
                ->where('idUser', $settig["idUser"])
                ->update(['value' => $val]);
        }
    }
}
