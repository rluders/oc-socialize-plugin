<?php

namespace RLuders\Socialize\Modules\Album\Components;

use Auth;
use Response;
use Cms\Classes\ComponentBase;

class Album extends ComponentBase
{
    public function onRun()
    {

    }

    public function defineProperties()
    {
        return [];
    }

    /**
     * Component details
     *
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name' => 'Album',
            'description' => 'Show the user album list'
        ];
    }
}