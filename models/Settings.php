<?php

namespace RLuders\Socialize\Models;

use Model;
use Validator;
use ValidationException;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'rluders_socialize_settings';
    public $settingsFields = 'fields.yaml';
}
