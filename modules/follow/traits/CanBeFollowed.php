<?php

namespace RLuders\Socialize\Modules\Follow;

use RLuders\Socialize\Models\FollowRelation;

trait CanBeFollowed
{
    /**
     * Get the model where the trait is active
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this;
    }

    public function followers()
    {
        return $this->morphMany(FollowRelation::class, 'followers');
    }
}