<?php

namespace Core\Interfaces;

use App\Enums\SettingsEnum;

interface  ISettingsRepository {
     public function getSetting(SettingsEnum $settings);
}
