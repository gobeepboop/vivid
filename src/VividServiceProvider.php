<?php

namespace Beep\Vivid;

use Beep\Vivid\Database\MySqlConnection;
use Illuminate\Database\Connection;
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
        Connection::resolverFor('mysql', function () {
            return new MySqlConnection(...func_get_args());
        });
    }
}
