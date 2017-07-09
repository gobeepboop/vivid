<?php

namespace Beep\Vivid\Database\Concerns;

use Beep\Vivid\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

trait ResolvesBlueprint
{
    /**
     * {@inheritdoc}
     */
    final public function getSchemaBuilder()
    {
        return $this->resolveBlueprint(parent::getSchemaBuilder());
    }

    /**
     * Resolves the Blueprint.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    protected function resolveBlueprint(Builder $builder)
    {
        $builder->blueprintResolver(function ($table, $closure): Blueprint {
            return new Blueprint($table, $closure);
        });

        return $builder;
    }
}
