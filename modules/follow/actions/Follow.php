<?php

namespace RLuders\Socialize\Modules\Follow\Actions;

use Auth;
use RainLab\User\Models\User;
use RLuders\Socialize\Classes\AbstractAction;


class Follow extends AbstractAction
{
    protected function handle(array $data = null)
    {
        if (!isset($data['user_id'])) {
            return null;
        }

        $user = User::find($data['user_id']);
        return $user ? Auth::getUser()->befriend($user) : null;
    }
}