<?php

namespace RLuders\Socialize\Modules\Activity;

use Config;
use RainLab\User\Models\User;
use RLuders\Socialize\Models\Activity;
use October\Rain\Support\ServiceProvider;

class ActivityServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        User::extend(
            function ($model) {
                // Activity relationship
                $model->hasMany['activities'] = [Activity::class, 'order' => 'updated_at desc'];
            }
        );
    }
}