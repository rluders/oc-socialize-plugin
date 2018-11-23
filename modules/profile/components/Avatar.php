<?php

namespace RLuders\Socialize\Modules\Profile\Components;

use Auth;
use Cms\Classes\ComponentBase;
use RLuders\Socialize\Modules\Profile\Actions\UploadAvatar;

class Avatar extends ComponentBase
{
    /**
     * Initialize the component
     *
     * @return void
     */
    public function init()
    {
        // Nothing here for now
    }

    /**
     * Executes when the component runs
     *
     * @return void
     */
    public function onRun()
    {
        // @TODO How to disable this when the Avatar changer is not enabled?
        $this->addJs($this->assetPath . '/assets/vendor/croppr/croppr.min.js');
        $this->addJs($this->assetPath . '/assets/js/avatar.js');

        $this->addCss($this->assetPath . '/assets/vendor/croppr/croppr.min.css');
        $this->addCss($this->assetPath . '/assets/css/avatar.css');
    }

    public function defineProperties()
    {
        return [
            // width, height, crop type, others...
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
            'name' => 'User Avatar',
            'description' => 'Allow the user to change his avatar'
        ];
    }

    public function onSubmit()
    {
        $file = \Input::file('image');

        $action = new UploadAvatar();
        $result = $action->execute(
            [
                'user' => Auth::getUser(),
                'path' => $file->getPathName()
            ]
        );

        // @TODO Convert to responsable
        if ($result) {

            return [
                'title' => 'Success!',
                'message' => 'Avatar updated.',
                'type' => 'success',
                'image' => $user->avatar->getPath()
            ];

        }

        return [
            'title' => 'Ops!',
            'message' => 'Unable to save your data.',
            'type' => 'error'
        ];
    }
}