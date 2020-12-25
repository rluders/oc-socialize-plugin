<?php

namespace RLuders\Socialize\Classes;

abstract class AbstractAction
{
    /**
     * Check if class is booted
     *
     * @var boolean
     */
    protected $booted = false;

    /**
     * Constructor
     *
     * @return void
     */
    final public function __construct()
    {
        $this->boot();
    }

    /**
     * Bootstrap the action to prepara what it needs to execute
     *
     * @return void
     */
    public function boot()
    {
        static::bootTraits();
        $this->booted = true;
    }

    /**
     * Boot all the bootable traits
     *
     * @return void
     */
    protected static function bootTraits()
    {
        $class = static::class;

        foreach (class_uses_recursive($class) as $trait) {
            $className = str_replace('Trait', null, class_basename($trait));
            if (method_exists($class, $method = 'boot' . $className)) {
                forward_static_call([$class, $method]);
            }
        }
    }

    /**
     * Executes the action
     *
     * @param array $data
     * @return mixed|void
     */
    final public function execute(array $data = null)
    {
        if (!$this->booted) {
            $this->boot();
        }

        return $this->handle($data);
    }

    /**
     * The action handler
     *
     * @param array $data
     * @return mixed|void
     */
    abstract protected function handle(array $data = null);
}
