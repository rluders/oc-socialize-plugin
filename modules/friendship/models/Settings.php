<?php

namespace RLuders\Socialize\Modules\Friendship\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'rluders_socialize_settings_friendship';
    public $settingsFields = 'fields.yaml';
}
