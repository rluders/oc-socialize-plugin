<?php

namespace RLuders\Socialize\Modules\Friendship\Components;

use Auth;
use Cms\Classes\ComponentBase;
use RainLab\User\Models\User;

class UserList extends ComponentBase
{
    public function onRun()
    {
        $friends = User::paginate($this->property('maxItems'));
        $this->page['friends'] = $friends;
    }

    /**
     * Component details
     *
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name' => 'User List',
            'description' => 'Display the list of users.'
        ];
    }

    public function defineProperties()
    {
        return [
            'maxItems' => [
                'title'             => 'Max items',
                'description'       => 'The most amount of todo items allowed',
                'default'           => 16,
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'The Max Items property can contain only numeric symbols'
            ]
        ];
    }
}