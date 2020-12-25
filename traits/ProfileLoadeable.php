<?php

namespace RLuders\Socialize\Traits;

use RainLab\User\Models\User;

trait ProfileLoadeable
{
    /**
     * Load the user profile to the page variables
     *
     * @return void
     */
    public function loadProfile()
    {
        $slug = strtolower($this->param('user'));
        $key = strtolower($this->property('userSlug', 'username'));

        $user = User::where($key, $slug)->first();
        $this->page['socialize'] = [
            'user' => $user
        ];
    }
}
