<?php

namespace RLuders\Socialize\Modules\Friendship;

use App;
use Auth;
use Event;
use Config;
use RainLab\User\Models\User;
use RLuders\Socialize\Models\Friendship;
use RLuders\Socialize\Classes\AbstractModuleServiceProvider;
use RLuders\Socialize\Modules\Friendship\Behaviors\Friendable;
use RLuders\Socialize\Modules\Friendship\Actions\AcceptFriendship;

class FriendshipServiceProvider extends AbstractModuleServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadConfig();
        $this->extendUser();
        $this->registerSettings();
        $this->registerRoutes();
    }

    /**
     * Load module configuration
     *
     * @return void
     */
    protected function loadConfig()
    {
        $config = include_once realpath(__DIR__ . '/config/friendships.php');
        Config::set('friendships', $config);
    }

    /**
     * Extend the user to create the relationship with friendship table
     *
     * @return void
     */
    protected function extendUser()
    {
        User::extend(
            function ($model) {
                // Implement the friendship behaviour to user
                $model->implement[] = Friendable::class;

                // Friends relationship
                $model->morphMany['friends'] = [
                    Friendship::class,
                    'name' => 'sender'
                ];

                // Friend groups relationship
                $model->morphMany['groups'] = [
                    FriendFriendshipGroups::class,
                    'name' => 'friendship'
                ];
            }
        );
    }

    /**
     * Add the configuration settings to Friendship on the OctoberCMS Backend
     *
     * @return void
     */
    protected function registerSettings()
    {
        Event::listen(
            'system.settings.extendItems',
            function ($settings) {
                $settings->registerSettingItems(
                    'Rluders.Socialize_Friendship',
                    [
                        'settings' => [
                            'label'       => 'Friendship',
                            'description' => 'Friendship Module Configuration',
                            'category'    => 'rluders.socialize::lang.system.categories.socialize',
                            'icon'        => 'icon-user',
                            'class'       => 'RLuders\Socialize\Modules\Friendship\Models\Settings',
                            'order'       => 600,
                            'permissions' => [
                                'rluders.socialize.access_settings',
                                'rluders.socialize.access_settings.friendship'
                            ],
                        ]
                    ]
                );
            }
        );
    }

    /**
     * Register module API routes
     *
     * @return void
     */
    protected function registerRoutes()
    {
        include_once __DIR__ . "/routes.php";
    }

    /**
     * Register plugin components
     *
     * @return array
     */
    public static function getComponents()
    {
        return [
            \RLuders\Socialize\Modules\Friendship\Components\Friendship::class => 'userFriendship',
            \RLuders\Socialize\Modules\Friendship\Components\UserList::class => 'userList',
        ];
    }

    /**
     * Register plugin markup tags
     *
     * @return array
     */
    public static function getMarkupTags()
    {
        return [
            'functions' => [
                'has_friend_request_from' => function ($userId) {
                    $user = User::find($userId);
                    if (!$user) {
                        return false;
                    }
                    return Auth::getUser()->hasFriendRequestFrom($user);
                },
                'has_sent_friend_request_to' => function ($userId) {
                    $user = User::find($userId);
                    if (!$user) {
                        return false;
                    }
                    return Auth::getUser()->hasSentFriendRequestTo($user);
                },
                'is_friend_with' => function ($userId) {
                    $user = User::find($userId);
                    if (!$user) {
                        return false;
                    }
                    return Auth::getUser()->isFriendWith($user);
                },
                'has_blocked' => function ($userId) {
                    $user = User::find($userId);
                    if (!$user) {
                        return false;
                    }
                    return Auth::getUser()->hasBlocked($user);
                },
                'is_blocked_by' => function ($userId) {
                    $user = User::find($userId);
                    if (!$user) {
                        return false;
                    }
                    return $user->hasBlocked(Auth::getUser());
                },
                'can_be_friend' => function ($userId) {
                    $user = User::find($userId);
                    if (!$user || Auth::getUser()->id == $user->id) {
                        return false;
                    }
                    return Auth::getUser()->canBefriend($user);
                },
                'user_in_group' => function ($userId, string $group) {
                    $user = User::find($userId);
                    if (!$user) {
                        return false;
                    }
                    $groups = $user->groups()->lists('name', 'code');
                    return array_key_exists($group, $groups);
                },
            ]
        ];
    }
}