<?php

namespace RLuders\Socialize\Modules\Profile\Behaviors;

use Illuminate\Database\Eloquent\Model;
use October\Rain\Extension\ExtensionBase;
use RLuders\Socialize\Modules\Profile\Traits\HasCover as HasCoverTrait;

class HasCover extends ExtensionBase
{
    use HasCoverTrait;

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
     * Overwriting the getModel from trait
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }
}
