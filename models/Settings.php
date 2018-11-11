<?php

namespace RLuders\Socialize\Models;

use Model;
use Event;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'rluders_socialize_settings';
    public $settingsFields = 'fields.yaml';

    public function getActiveModules()
    {
        $modules = [
            'activity',
            'friendship',
            'profile'
        ];

        Event::fire('rluders.socialize.get_active_modules', [&$modules]);

        foreach ($this->attributes as $key => $value) {
            if ($value == 0) {
                continue;
            }
            if (strpos($key, 'module_') !== false) {
                $modules[] = str_replace('module_', '', $key);
            }
        }

        return $modules;
    }
}
