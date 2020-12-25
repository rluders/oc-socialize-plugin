<?php

namespace RLuders\Socialize\Modules\Activity\Behaviors;

use ReflectionClass;
use October\Rain\Extension\ExtensionBase;
use Illuminate\Database\Eloquent\Model;
use RLuders\Socialize\Modules\Activity\Traits\RecordsActivity
    as RecordsActivityTrait;

class RecordsActivity extends ExtensionBase
{
    use RecordsActivityTrait;

    /**
     * @var Model
     */
    protected $model;

    /**
     * The class constructor
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get the model object for trait use
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }
}
