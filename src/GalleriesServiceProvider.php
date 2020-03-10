<?php

namespace MsCart\Galleries;

use Blade;
use Illuminate\Support\ServiceProvider;

class GalleriesServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
         $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'galleries');
         $this->loadViewsFrom(__DIR__.'/../resources/views', 'galleries');
         $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
         $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        \App::setLocale(\Setting::get('admin_language'));
        $menu =  \Menu::get('Sidebar');
        $acl = $menu->add(__('galleries::gallery.name'),    ['segment2'=>'galleries', 'icon'=> 'icon-images3'])->nickname('gallery')->data('order', 1);
        $menu->gallery->add(__('galleries::gallery.add_gallery'),config('app.admin_prefix').'/galleries/create');
        $menu->gallery->add(__('galleries::gallery.list'),config('app.admin_prefix').'/galleries');


        Blade::if('haveChildren', function($id){
            $currentGal = Gallery::find($id);
            if (count($currentGal->children) > 0)
                return true;
            else
                return false;
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/galleries.php', 'galleries');

        // Register the service the package provides.
        $this->app->singleton('galleries', function ($app) {
            return new Galleries;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['galleries'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/galleries.php' => config_path('galleries.php'),
        ], 'galleries.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/mscart'),
        ], 'galleries.views');*/

        // Publishing assets.
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/mscart/galleries'),
        ], 'galleries.views');

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/mscart'),
        ], 'galleries.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
