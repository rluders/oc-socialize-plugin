<?php

namespace RLuders\Socialize\Modules\Video;

use Config;
use RainLab\User\Models\User;
use RLuders\Socialize\Models\Video;
use October\Rain\Support\ServiceProvider;

class VideoServiceProvider extends ServiceProvider
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
                // Friends relationship
                $model->hasMany['videos'] = [Video::class, 'scope' => 'orderItems'];
            }
        );
    }
}