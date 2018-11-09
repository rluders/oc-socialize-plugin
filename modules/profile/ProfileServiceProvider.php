<?php

namespace RLuders\Socialize\Modules\Profile;

use Config;
use RLuders\Socialize\Models\File;
use RainLab\User\Models\User;
use Cms\Classes\ComponentManager;
use October\Rain\Support\ServiceProvider;
use RainLab\User\Controllers\Users as UsersController;

class ProfileServiceProvider extends ServiceProvider
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
                // Profile cover relationship
                $model->attachOne['cover'] = [File::class];
            }
        );

        UsersController::extendFormFields(
            function ($form, $model, $context) {
                $form->addTabFields(
                    [
                        'cover' => [
                            'label' => 'Cover',
                            'type' => 'fileupload',
                            'mode' => 'image',
                            'imageHeight' => 360, // @TODO Get it from plugin config
                            'imageWidth' => 1920, // @TODO Get it from plugin config
                            'tab' => 'rainlab.user::lang.user.account'
                        ]
                    ]
                );
            }
        );
    }

    /**
     * Register plugin components
     *
     * @return void
     */
    public static function getComponents()
    {
        return [
            \RLuders\Socialize\Modules\Profile\Components\Profile::class => 'userProfile',
            \RLuders\Socialize\Modules\Profile\Components\Avatar::class => 'userAvatar',
            \RLuders\Socialize\Modules\Profile\Components\Cover::class => 'userCover',
        ];
    }
}