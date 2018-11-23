<?php

namespace RLuders\Socialize\Modules\Comment\Traits;

use RLuders\Socialize\Models\Comments;

trait Commentable 
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

    /**
     * Get all the comments
     *
     * @return mixed
     */
    public function comments()
    {
        return $this->getModel()->morphMany(Comments::class, 'commentable');
    }
}