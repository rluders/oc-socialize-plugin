<?php

namespace RLuders\Socialize\Modules\Profile\Components;

use Auth;
use Response;
use Cms\Classes\ComponentBase;

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
        // we assume you post `base64` string in `image`
        $image = input('image');

        $user = Auth::getUser();
        $filename = "{$user->id}_avatar.jpg";

        // attach that $file to Model
        $user->avatar = $this->createFile($image, $filename);
        if ($user->save()) {

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

    protected function createFile($data, $filename)
    {
        $image = str_replace('data:image/jpeg;base64,', '', $data);
        $image = str_replace(' ', '+', $image);
        $imageData = base64_decode($image);

        return (new \RLuders\Socialize\Models\File)->fromData($imageData, $filename);
    }
}