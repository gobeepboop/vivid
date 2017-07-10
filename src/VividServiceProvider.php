<?php

namespace Beep\Vivid;

use Beep\Vivid\Database\MySqlConnection;
use Beep\Vivid\Scout\Engines\AlgoliaEngine;
use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\EngineManager as ScoutEngineManager;

class VividServiceProvider extends ServiceProvider
{
    /**
     * Register the Service Provider.
     *
     * @return void
     */
    public function register(): void
    {
        // MySQL Connection
        Connection::resolverFor('mysql', function () {
            return new MySqlConnection(...func_get_args());
        });
    }

    /**
     * Boot the Service Provider.
     *
     * @return void
     */
    public function boot(): void
    {
        resolve(ScoutEngineManager::class)->extend('algolia', function () {
            return $this->app->make(AlgoliaEngine::class);
        });
    }
}
