<?php

namespace Beep\Vivid\Scout\Engines;

use Beep\Vivid\Database\Eloquent\Collection;
use Laravel\Scout\Engines\AlgoliaEngine as Base;

class AlgoliaEngine extends Base
{
    /**
     * Map the given results to instances of the given model.
     *
     * @param  mixed  $results
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return Collection
     */
    public function map($results, $model)
    {
        if (count($results['hits']) === 0) {
            return Collection::make();
        }

        $keys = collect($results['hits'])
            ->pluck('objectID')->values()->all();

        $models = $model->whereIn(
            $model->getQualifiedKeyName(), $keys
        )->get()->keyBy('uuid');

        return Collection::make($results['hits'])->map(function ($hit) use ($model, $models) {
            $key = $hit['objectID'];

            if (isset($models[$key])) {
                return $models[$key];
            }
        })->filter();
    }
}
