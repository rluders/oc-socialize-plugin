<?php

namespace RLuders\Socialize;

use Auth;
use RainLab\User\Models\User;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;
use RLuders\Socialize\Models\Settings;

/**
 * Socialize Plugin Information File.
 */
class Plugin extends PluginBase
{
    /**
     * Plugin dependencies.
     *
     * @var array
     */
    public $require = [
        'RainLab.User',
        'Esroyo.UserProfile'
    ];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'rluders.socialize::lang.plugin.name',
            'description' => 'rluders.socialize::lang.plugin.description',
            'author'      => 'Ricardo Lüders',
            'icon'        => 'icon-user-secret',
        ];
    }

    /**
     * Register the plugin settings
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'rluders.socialize::lang.settings.menu_label',
                'description' => 'rluders.socialize::lang.settings.menu_description',
                'category'    => 'rluders.socialize::lang.system.categories.socialize',
                'icon'        => 'icon-cog',
                'class'       => 'RLuders\Socialize\Models\Settings',
                'order'       => 500,
                'permissions' => ['rluders.socialize.access_settings'],
            ]
        ];
    }

    /**
     * Register plugin permissions
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'rluders.socialize.access_settings' => [
                'tab' => 'rluders.socialize::lang.plugin.name',
                'label' => 'rluders.socialize::lang.permissions.settings'
            ]
        ];
    }

    /**
     * Get active plugin's modules
     *
     * @return array
     */
    protected function getPluginModules()
    {
        return Settings::instance()->getActiveModules();
    }

    /**
     * Get the namespace + class to the module's service provider
     *
     * @param string $module
     * @return string
     */
    protected function getPluginModuleServiceProviderClassName($module)
    {
        return '\\RLuders\\Socialize\\Modules\\' .
            ucfirst($module) . '\\' .
            ucfirst($module) . 'ServiceProvider';
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Plugin bootstrap
     *
     * @return void
     */
    public function boot()
    {
        $modules = $this->getPluginModules();
        foreach ($modules as $module) {
            $className = $this->getPluginModuleServiceProviderClassName($module);
            if (class_exists($className)) {
                $this->app->register($className);
            }
        }
    }

    /**
     * Register plugin components
     *
     * @return array
     */
    public function registerComponents()
    {
        $components = [];

        $modules = $this->getPluginModules();
        foreach ($modules as $module) {
            $className = $this->getPluginModuleServiceProviderClassName($module);
            if (class_exists($className) && method_exists($className, 'getComponents')) {
                $components += $className::getComponents();
            }
        }

        return $components;
    }

    /**
     * Register the markuptags for the template engine
     *
     * @return array
     */
    public function registerMarkupTags()
    {
        $markupTags = [];

        $modules = $this->getPluginModules();
        foreach ($modules as $module) {
            $className = $this->getPluginModuleServiceProviderClassName($module);
            if (class_exists($className) && method_exists($className, 'getMarkupTags')) {
                $markupTags += $className::getMarkupTags();
            }
        }

        return $markupTags;
    }
}
