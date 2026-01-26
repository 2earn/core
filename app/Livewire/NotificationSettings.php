<?php

namespace App\Livewire;

use App\Enums\NotificationSettingEnum;
use App\Enums\SettingsEnum;
use App\Services\Settings\SettingService;
use App\Services\settingsManager;
use App\Services\UserNotificationSettingService;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class NotificationSettings extends Component
{
    private settingsManager $settingsManager;
    private SettingService $settingService;
    private UserNotificationSettingService $userNotificationSettingService;

    public $setting_notif;
    public $nbrSms;
    public $nbrSmsPossible;

    protected $rules = [
        'setting_notif.*.value' => 'required',
    ];

    public function mount(
        settingsManager $settingsManager,
        SettingService $settingService,
        UserNotificationSettingService $userNotificationSettingService
    ) {
        $this->settingsManager = $settingsManager;
        $this->settingService = $settingService;
        $this->userNotificationSettingService = $userNotificationSettingService;
    }

    public function save()
    {
        // Prepare settings array for bulk update
        $settings = [];
        foreach ($this->setting_notif as $setting) {
            $value = ($setting->id == 19) ? $this->nbrSms : $setting->value;

            $settings[] = [
                'idNotification' => $setting->idNotification,
                'idUser' => $setting->idUser,
                'value' => $value
            ];
        }

        // Update settings using service
        $result = $this->userNotificationSettingService->updateMultipleSettings($settings);

        $flashType = $result['success'] ? 'success' : 'warning';
        return redirect()->route('notification_settings', ['locale' => app()->getLocale()])
            ->with($flashType, Lang::get($result['message']));
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
