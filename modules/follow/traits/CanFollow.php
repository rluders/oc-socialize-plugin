<?php

namespace RLuders\Socialize\Modules\Follow;

use RLuders\Socialize\Models\FollowRelation;

trait CanFollow
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

    public function followees()
    {
        return $this->getModel()->morphMany(FollowRelation::class, 'followee');
    }

    public function follow($followee)
    {
        // @TODO Can follow
        return $this->getModel()
            ->followees()
            ->create(
                [
                    'followee_id' => $followee->id,
                    'followee_type' => get_class($followee)
                ]
            );
    }

    public function unfollow($followee)
    {
        return false;
    }
}