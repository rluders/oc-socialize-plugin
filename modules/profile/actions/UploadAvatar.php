<?php

namespace RLuders\Socialize\Modules\Profile\Actions;

use RLuders\Socialize\Classes\AbstractAction;

class UploadAvatar extends AbstractAction
{
    protected function handle(array $data = null)
    {
        if (!isset($data['file'])) {
            return null;
        }

        $file = $data['file'];
        $user = isset($data['user'])
            ? $data['user']
            : Auth::getUser();

        $user->avatar = $this->createFile(
            $file,
            "{$user->id}_avatar.jpg"
        );

        return $user->save();
    }

    protected function createFile($file, $filename)
    {
        return (new \System\Models\File)
            ->create(
                [
                    'data' => $file,
                    'file_name' => $filename
                ]
            );
    }
}