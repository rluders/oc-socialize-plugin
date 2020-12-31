<?php

namespace RLuders\Socialize\Modules\Profile;

use Config;
use Event;
use RainLab\User\Models\User;
use RLuders\Socialize\Models\File;
use RainLab\User\Controllers\Users as UsersController;
use RLuders\Socialize\Classes\AbstractModuleServiceProvider;

class ProfileServiceProvider extends AbstractModuleServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        $this->extendUser();
        $this->extendUserController();
        // $this->registerSettings();
    }

    protected function extendUser()
    {
        User::extend(
            function ($model) {
                // Profile cover relationship
                $model->attachOne['cover'] = File::class;
            }
        );
    }

    protected function extendUserController()
    {
        UsersController::extendFormFields(
            function ($form, $model, $context) {
                if (!$model instanceof User) {
                    return;
                }

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

    protected function registerSettings()
    {
        Event::listen(
            'system.settings.extendItems',
            function ($settings) {
                $settings->registerSettingItems(
                    'Rluders.Socialize_Profile',
                    [
                        'settings' => [
                            'label'       => 'Profile',
                            'description' => 'Profile Module Configuration',
                            'category'    => 'rluders.socialize::lang.system.categories.socialize',
                            'icon'        => 'icon-user',
                            'class'       => 'RLuders\Socialize\Modules\Profile\Models\Settings',
                            'order'       => 600,
                            'permissions' => [
                                'rluders.socialize.access_settings',
                                'rluders.socialize.access_settings.profile'
                            ],
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
