<?php

namespace RLuders\Socialize\Modules\Album;

use Config;
use RainLab\User\Models\User;
use RLuders\Socialize\Models\Album;
use October\Rain\Support\ServiceProvider;

class AlbumServiceProvider extends ServiceProvider
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
                // Albums relationship
                $model->hasMany['albums'] = [Album::class, 'scope' => 'orderItems'];
            }
        );
    }
}