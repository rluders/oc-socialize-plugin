<?php

namespace RLuders\Socialize\Modules\Friendship\Components;

use Auth;
use Cms\Classes\ComponentBase;
use RLuders\Socialize\Modules\Friendship\Actions\RemoveFriendship;
use RLuders\Socialize\Modules\Friendship\Actions\SendFriendship;
use RLuders\Socialize\Modules\Friendship\Actions\AcceptFriendship;
use RLuders\Socialize\Modules\Friendship\Actions\CancelFriendship;
use RLuders\Socialize\Modules\Friendship\Actions\DeclineFriendship;
use RLuders\Socialize\Traits\ProfileLoadeable;

class Friendship extends ComponentBase
{
    use ProfileLoadeable;

    /**
     * Initialize the component
     *
     * @return void
     */
    public function init()
    {
        $this->loadProfile();
    }

    /**
     * Executes when the component runs
     *
     * @return void
     */
    public function onRun()
    {
        if (Auth::check()) {
            $this->addJs($this->assetPath . '/assets/js/friendship.js');
        }
    }

    /**
     * Refresh the component after the actions
     *
     * @return void
     */
    public function onRefresh()
    {
        return [
            '#friendshipActions' => $this->renderPartial('@actions')
        ];
    }

    /**
     * Component details
     *
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name' => 'Friendship',
            'description' => 'Controls the friendship.'
        ];
    }

    /**
     * Get user id
     *
     * @return int
     */
    protected function getUserId()
    {
        return (int)post('user');
    }

    /**
     * Send friend request
     *
     * @return void
     */
    public function onSend()
    {
        (new SendFriendship)->execute(['user_id' => $this->getUserId()]);
    }

    /**
     * Unfriend
     *
     * @return void
     */
    public function onRemove()
    {
        (new RemoveFriendship)->execute(['user_id' => $this->getUserId()]);
    }

    /**
     * Accept the friend request
     *
     * @return void
     */
    public function onAccept()
    {
        (new AcceptFriendship)->execute(['user_id' => $this->getUserId()]);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function onDecline()
    {
        (new DeclineFriendship)->execute(['user_id' => $this->getUserId()]);
    }

    /**
     * Cancel the friend request
     *
     * @return void
     */
    public function onCancel()
    {
        (new CancelFriendship)->execute(['user_id' => $this->getUserId()]);
    }
}
