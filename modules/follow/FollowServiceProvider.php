<?php

namespace RLuders\Socialize\Modules\Follow;

use Config;
use RainLab\User\Models\User;
use RLuders\Socialize\Models\FollowRelation;
use October\Rain\Support\ServiceProvider;

class FollowServiceProvider extends ServiceProvider
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
                // Follow relationship
                $model->hasMany['followee'] = [FollowRelation::class];
            }
        );
    }

    public static function getComponents()
    {
        return [
            \RLuders\Socialize\Modules\Follow\Components\Follow::class => 'followButton'
        ];
    }
}