<?php

namespace App\Livewire;

use App\Enums\NotificationSettingEnum;
use App\Enums\SettingsEnum;
use App\Services\Settings\SettingService;
use App\Services\settingsManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class NotificationSettings extends Component
{
    private settingsManager $settingsManager;
    private SettingService $settingService;
    public $setting_notif;
    public $nbrSms;
    public $nbrSmsPossible;
    protected $rules = [
        'setting_notif.*.value' => 'required',
    ];

    public function mount(settingsManager $settingsManager, SettingService $settingService)
    {
        $this->settingsManager = $settingsManager;
        $this->settingService = $settingService;
    }


    public function save()
    {
        try {
            foreach ($this->setting_notif as $setting) {
                if ($setting->id == 19)
                    $val = $this->nbrSms;
                else
                    $val = $setting->value;
                DB::table('user_notification_setting')
                    ->where('idNotification', $setting->idNotification)
                    ->where('idUser', $setting->idUser)
                    ->update(['value' => $val]);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('notification_settings', ['locale' => app()->getLocale()])->with('warning', Lang::get('Notifications setting saving failed'));
        }
        return redirect()->route('notification_settings', ['locale' => app()->getLocale()])->with('success', Lang::get('Notifications setting saved successfully'));
    }

    public function render(settingsManager $settingsManager)
    {
        $this->settingsManager = $settingsManager;
        $this->setting_notif = $this->settingsManager->getNotificationSetting($this->settingsManager->getAuthUser()->idUser);
        $this->nbrSms = $this->setting_notif->where('id', '=', NotificationSettingEnum::SMSByWeek->value)->first()->value;
        $this->nbrSmsPossible = $this->settingService->getDecimalValue(SettingsEnum::NbrSmsPossible->value);
        return view('livewire.notification-settings')->extends('layouts.master')->section('content');
    }

}
