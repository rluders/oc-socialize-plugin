<?php

namespace RLuders\Socialize\Models;

use Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RLuders\Socialize\Modules\Activity\Traits\RecordsActivity;

class Video extends Model
{
    use SoftDeletes;
    use RecordsActivity;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'rluders_socialize_videos';

    /**
     * One-to-One Relationships
     *
     * @var array
     */
    public $attachOne = [
        'file' => [
            'RLuders\Socialize\Models\File',
            'delete' => true
        ],
        'cover' => [
            'RLuders\Socialize\Models\File',
            'delete' => true
        ],
    ];

    /**
     * Fillable columns on table
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'privacy',
        'status',
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

    public function scopeOrderItems($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}