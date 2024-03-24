<?php

namespace Core\Interfaces;

use Core\Enum\SettingsEnum;

interface  ISettingsRepository {
     public function getSetting(SettingsEnum $settings);
}
