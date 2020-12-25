<?php

namespace RLuders\Socialize\Models;

use Model;
use Illuminate\Database\Eloquent\Builder;

class FollowRelation extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'rluders_socialize_follow_relations';

    /**
     * Fillable columns on table
     *
     * @var array
     */
    protected $fillable = [
        'follower_id',
        'follower_type',
        'followee_id',
        'followee_type'
    ];

    /**
     * Enable/Disable timestamps at the table
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * Who/What is following
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function follower()
    {
        return $this->morphTo('follower');
    }

    /**
     * Who/What is being followed
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function followee()
    {
        return $this->morphTo('followee');
    }

    /**
     * Scope the popular
     *
     * @param \Illuminate\Database\Eloquent\Builde $query
     * @param string|null $type
     *
     * @return void
     */
    public function scopePopular($query, $type = null)
    {
        $query->select('followee_id', 'followee_type', \DB::raw('COUNT(*) AS count'))
            ->groupBy('followee_id', 'followee_type')
            ->orderByDesc('count');

        return $query;
    }
}
