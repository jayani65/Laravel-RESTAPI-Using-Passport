<?php

namespace Spatie\Fractal;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use Spatie\Fractal\Console\Commands\TransformerMakeCommand;
use Illuminate\Foundation\Application as LaravelApplication;

class FractalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->setupConfig();

        if ($this->app->runningInConsole()) {
            $this->commands([
                TransformerMakeCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton('fractal', function (...$arguments) {
            return fractal(...$arguments);
        });

        $this->app->alias('fractal', Fractal::class);
    }

    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../config/fractal.php');

        if ($this->app instanceof LaravelApplication) {
            $this->publishes([$source => config_path('fractal.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('fractal');
        }

        $this->mergeConfigFrom($source, 'fractal');
    }
}
