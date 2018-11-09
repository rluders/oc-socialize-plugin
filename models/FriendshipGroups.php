<?php

namespace RLuders\Socialize\Models;

use Model;

class FriendshipGroups extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'rluders_socialize_friendship_groups';

    /**
     * Fillable columns on table
     *
     * @var array
     */
    protected $fillable = [
        'friendship_id',
        'group_id',
        'friend_id',
        'friend_type'
    ];

    /**
     * Enable/Disable timestamps at the table
     *
     * @var boolean
     */
    public $timestamps = false;
}
