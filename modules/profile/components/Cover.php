<?php

namespace RLuders\Socialize\Modules\Profile\Components;

use Auth;
use Response;
use Cms\Classes\ComponentBase;

class Cover extends ComponentBase
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
        // Nothing here for now
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
            'name' => 'User Profile Cover',
            'description' => 'Put the user profile cover action into the view.'
        ];
    }

    public function onSubmit()
    {
        // we assume you post `base64` string in `image`
        $image = input('image');

        $user = Auth::getUser();
        $filename = "{$user->id}_cover.jpg";

        // attach that $file to Model
        $user->cover = $this->createFile($image, $filename);
        if ($user->save()) {
            return [
                'title' => 'Success!',
                'message' => 'Cover updated.',
                'type' => 'success',
                'image' => $user->cover->getPath()
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
