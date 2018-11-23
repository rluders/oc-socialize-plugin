<?php

namespace RLuders\Socialize\Modules\Comment\Actions;

use RLuders\Socialize\Classes\AbstractAction;


class DeleteComment extends AbstractAction
{
    protected function handle(array $data = null)
    {
        return 'Delete comment';
    }
}