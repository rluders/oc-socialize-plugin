<?php

namespace RLuders\Socialize\Modules\Activity\Traits;

use ReflectionClass;
use RLuders\Socialize\Models\Activity;

/**
 * From on https://github.com/laracasts/Build-An-Activity-Feed-in-Laravel
 */
trait RecordsActivity
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
     * Register the necessary event listeners.
     *
     * @return void
     */
    protected static function bootRecordsActivity()
    {
        foreach (static::getModelEvents() as $event) {
            static::$event(
                function ($model) use ($event) {
                    $model->recordActivity($event);
                }
            );
        }
    }
    /**
     * Record activity for the model.
     *
     * @param  string $event
     * @return void
     */
    public function recordActivity($event)
    {
        Activity::create(
            [
                'subject_id' => $this->getModel()->id,
                'subject_type' => get_class($this->getModel()),
                'name' => $this->getActivityName($this->getModel(), $event),
                'user_id' => $this->getModel()->user_id
            ]
        );
    }
    /**
     * Prepare the appropriate activity name.
     *
     * @param  mixed  $model
     * @param  string $action
     * @return string
     */
    protected function getActivityName($model, $action)
    {
        $name = strtolower((new ReflectionClass($model))->getShortName());
        return "{$action}_{$name}";
    }
    /**
     * Get the model events to record activity for.
     *
     * @return array
     */
    protected static function getModelEvents()
    {
        if (isset(static::$recordEvents)) {
            return static::$recordEvents;
        }

        return [
            'created',
            'deleted',
            'updated',
        ];
    }
}
