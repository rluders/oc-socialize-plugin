<?php

namespace RLuders\Socialize\Modules\Album\Actions;

use RLuders\Socialize\Classes\AbstractAction;


class ListAlbums extends AbstractAction
{
    protected function handle(array $data = null)
    {
        return 'List of albums';
    }
}