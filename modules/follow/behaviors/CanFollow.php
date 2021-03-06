<?php

namespace RLuders\Socialize\Modules\Follow\Behaviors;

use October\Rain\Extension\ExtensionBase;
use Illuminate\Database\Eloquent\Model;
use RLuders\Socialize\Modules\Follow\Traits\CanFollow 
    as CanFollowTrait;

class CanFollow extends ExtensionBase
{
    use CanFollowTrait;
    
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