<?php

namespace Beep\Vivid;

use Illuminate\Database\MySqlConnection as IlluminateMySqlConnection;
use Beep\Vivid\Database\MySqlConnection;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(IlluminateMySqlConnection::class, MySqlConnection::class);
    }
}
