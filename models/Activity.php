<?php

namespace RLuders\Socialize\Models;

use Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'rluders_socialize_activities';

    /**
     * Fillable columns on table
     *
     * @var array
     */
    protected $fillable = [
        'subject_id',
        'subject_type',
        'name',
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
     * Get the subject of the activity.
     *
     * @return mixed
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Return the content type to identify the correct partial to be rendered
     *
     * @return string
     */
    public function getType()
    {
        return substr($this->name, strpos($this->name, "_") + 1);
    }

    /**
     * Get the action verb
     *
     * @return string
     */
    public function getVerb()
    {
        return substr($this->name, 0, strpos($this->name, "_"));
    }
}