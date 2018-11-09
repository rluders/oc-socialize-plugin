<?php

namespace RLuders\Socialize\Models;

use Model;
use Illuminate\Database\Eloquent\Builder;

class Friendship extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'rluders_socialize_friendships';

    /**
     * Fillable columns on table
     *
     * @var array
     */
    protected $fillable = [
        'sender_id',
        'sender_type',
        'recipient_id',
        'recipient_type',
        'status'
    ];

    /**
     * Enable/Disable timestamps at the table
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * Who request the friendship
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function sender()
    {
        return $this->morphTo('sender');
    }

    /**
     * Who will received the friendship request
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function recipient()
    {
        return $this->morphTo('recipient');
    }

    /**
     * Scope query for Recipient
     *
     * @param $query
     * @param Model $model
     * @return Builder
     */
    public function scopeWhereRecipient(Builder $query, Model $model)
    {
        return $query->where('recipient_id', $model->getKey())
            ->where('recipient_type', $model->getMorphClass());
    }

    /**
     * Scope query for Sender
     *
     * @param $query
     * @param Model $model
     * @return Builder
     */
    public function scopeWhereSender(Builder $query, Model $model)
    {
        return $query->where('sender_id', $model->getKey())
            ->where('sender_type', $model->getMorphClass());
    }

    /**
     * Scope query fro Groups
     *
     * @param Builder $query
     * @param Model $model
     * @param strind $groupSlug
     * @return Builder
     */
    public function scopeWhereGroup(Builder $query, Model $model, string $groupSlug)
    {
        $groupsPivotTable = 'rluders_socialize_friendship_groups';
        $friendsPivotTable = $this->table;

        $groupsAvailable = config('friendships.groups', []);

        if ('' !== $groupSlug && isset($groupsAvailable[$groupSlug])) {

            $groupId = $groupsAvailable[$groupSlug];

            $query->join(
                $groupsPivotTable,
                function ($join) use ($groupsPivotTable, $friendsPivotTable, $groupId, $model) {
                    $join->on("{$groupsPivotTable}.friendship_id", '=', "{$friendsPivotTable}.id")
                        ->where("{$groupsPivotTable}.group_id", '=', $groupId)
                        ->where(
                            function ($query) use ($groupsPivotTable, $friendsPivotTable, $model) {
                                $query
                                    ->where("{$groupsPivotTable}.friend_id", '!=', $model->getKey())
                                    ->where("{$groupsPivotTable}.friend_type", '=', $model->getMorphClass());
                            }
                        )
                        ->orWhere("{$groupsPivotTable}.friend_type", '!=', $model->getMorphClass());
                }
            );
        }

        return $query;
    }

    /**
     * Scope query between models
     *
     * @param $query
     * @param Model $sender
     * @param Model $recipient
     * @return Builder
     */
    public function scopeBetweenModels(Builder $query, Model $sender, Model $recipient)
    {
        $query->where(
            function ($queryIn) use ($sender, $recipient) {
                $queryIn->where(
                    function ($q) use ($sender, $recipient) {
                        $q->whereSender($sender)->whereRecipient($recipient);
                    }
                )->orWhere(
                    function ($q) use ($sender, $recipient) {
                        $q->whereSender($recipient)->whereRecipient($sender);
                    }
                );
            }
        );
    }
}
