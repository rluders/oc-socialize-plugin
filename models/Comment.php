<?php

namespace RLuders\Socialize\Models;

use Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    /**
     * Table name
     * 
     * @var string
     */
    protected $table = 'rluders_socialize_comments';

    /**
     * Fillable columns on table
     * 
     * @var array
     */
    protected $fillable = [
        'commentable_id',
        'commentable_type',
        'body',
        'user_id'
    ];

    /**
     * Enable/Disable timestamp at the table
     * 
     * @var boolean
     */
    public $timestamps = true;

    /**
     * Who owns this Video
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('RainLab\User\Models\User');
    }

    /**
     * Get all of the owning commentable models.
     *
     * @return mixed
     */
    public function commentable()
    {
        return $this->morphTo();
    }

}