<?php

namespace RLuders\Socialize\Modules\Follow\Components;

use Cms\Classes\ComponentBase;
use RLuders\Socialize\Modules\Follow\Action\Follow as FollowAction;
use RLuders\Socialize\Modules\Follow\Action\Unfollow as UnfollowAction;


class Follow extends ComponentBase
{
    /**
     * Initialize the component
     *
     * @return void
     */
    public function init()
    {
        //
    }

    /**
     * Executes when the component runs
     *
     * @return void
     */
    public function onRun()
    {
        // $this->addJs($this->assetPath . '/assets/js/friendship.js');
    }

    /**
     * Refresh the component after the actions
     *
     * @return void
     */
    public function onRefresh()
    {
        return [
            '#followActions' => $this->renderPartial('@actions')
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
            'name' => 'Follow',
            'description' => 'Controls the following.'
        ];
    }

    /**
     * Follow
     *
     * @return void
     */
    public function onFollow(FollowAction $action)
    {
        return $action->execute();
    }

    /**
     * Unfollow
     *
     * @return void
     */
    public function onUnfollow(UnfollowAction $action)
    {
        return $action->execute();
    }
}