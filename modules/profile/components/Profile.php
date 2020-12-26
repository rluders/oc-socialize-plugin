<?php

namespace RLuders\Socialize\Modules\Profile\Components;

use Auth;
use Response;
use Twig;
use RainLab\User\Models\User;
use Cms\Classes\ComponentBase;
use RLuders\Socialize\Traits\ProfileLoadeable;

class Profile extends ComponentBase
{
    use ProfileLoadeable;

    /**
     * Initialize the component
     *
     * @return void
     */
    public function init()
    {
        $this->loadProfile();
        $this->loadComponents();
    }

    /**
     * Executes when the component runs
     *
     * @return void
     */
    public function onRun()
    {
        $this->updateProfileTitle();
    }

    public function defineProperties()
    {
        return [
            'userSlug' => [
                'title'             => 'User Parameter',
                'description'       => 'The field that you want to use find the user.',
                'default'           => 'username',
                'type'              => 'string'
            ],
            'userPageTitle' => [
                'title'             => 'Change page title',
                'description'       => 'Change the page title to display the user name.',
                'default'           => '{{ user.name }}\'s Profile',
                'type'              => 'string'
            ],
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
            'name' => 'User Profile',
            'description' => 'Put the user profile data into view.'
        ];
    }

    protected function loadComponents()
    {
        // @TODO Is it possible to expose the component properties?
        $this->addComponent(
            'RLuders\Socialize\Modules\Profile\Components\Avatar',
            'avatar',
            []
        );
    }

    /**
     * Update profile page title
     *
     * @return void
     */
    protected function updateProfileTitle()
    {
        if (!isset($this->page['socialize'])) {
            return;
        }

        $text = $this->property('userPageTitle', 'name');
        if ($text) {
            $this->page->title = Twig::parse(
                $text,
                [
                    'user' => $this->page['socialize']['user']
                ]
            );
        }
    }
}
