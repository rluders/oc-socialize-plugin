<?php

namespace RLuders\Socialize\Modules\Friendship;

use Config;
use RainLab\User\Models\User;
use Cms\Classes\ComponentManager;
use System\Classes\MarkupManager;
use October\Rain\Support\ServiceProvider;
use RLuders\Socialize\Models\Friendship;
use RLuders\Socialize\Modules\Friendship\Behaviors\Friendable;

class FriendshipServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadConfig();

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