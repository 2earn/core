<?php

namespace App\Interfaces;

use App\Enums\SettingsEnum;

interface  ISettingsRepository {
     public function getSetting(SettingsEnum $settings);
}
