<?php

namespace Beep\Vivid;

use Beep\Vivid\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
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
        /** @var Builder $schemaBuilder */
        $schemaBuilder = $this->app->make(Builder::class);
        $schemaBuilder->blueprintResolver(function ($table, $closure) {
            return new Blueprint($table, $closure);
        });

        $this->app->instance(Builder::class, $schemaBuilder);
    }
}
