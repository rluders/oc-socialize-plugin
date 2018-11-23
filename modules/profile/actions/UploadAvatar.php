<?php

namespace RLuders\Socialize\Modules\Profile\Actions;

use RLuders\Socialize\Classes\AbstractAction;

class UploadAvatar extends AbstractAction
{
    protected function handle(array $data = null)
    {
        if (isset($data['path'])) {
            return null;
        }

        $user = isset($data['user']) ? $user : Auth::getUser();
        $path = $data['path'];

        $filename = "{$user->id}_avatar.jpg";

        $user->avatar = $this->createFile($path, $filename);

        return $user->save();
    }

    protected function createFile($filepath, $filename)
    {
        return (new \RLuders\Socialize\Models\File)
            ->fromData(
                file_get_contents($file->getPathName()),
                $filename
            );
    }
}