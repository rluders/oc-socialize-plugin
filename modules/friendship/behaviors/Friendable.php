<?php

namespace RLuders\Socialize\Modules\Friendship\Behaviors;

use Illuminate\Database\Eloquent\Model;
use October\Rain\Extension\ExtensionBase;
use RLuders\Socialize\Modules\Friendship\Traits\Friendable as FriendableTrait;

class Friendable extends ExtensionBase
{
    use FriendableTrait;

    /**
     * @var Model
     */
    protected $sender;

    /**
     * The class constructor
     *
     * @param Model $sender
     */
    public function __construct(Model $sender)
    {
        $this->sender = $sender;
    }

    /**
     * Overwriting the getSender from trait
     *
     * @return Model
     */
    public function getSender()
    {
        return $this->sender;
    }
}