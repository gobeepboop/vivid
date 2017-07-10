<?php

namespace Beep\Vivid;

use Beep\Vivid\Database\Eloquent\Collection;
use Beep\Vivid\Jobs\MakeSearchable;
use Laravel\Scout\Searchable as Base;

trait Searchable
{
    use Base;

    /**
     * {@inheritdoc}
     */
    public function queueMakeSearchable($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        if (! config('scout.queue')) {
            return $models->first()->searchableUsing()->update($models);
        }

        dispatch((new MakeSearchable(new Collection($models->all())))
            ->onQueue($models->first()->syncWithSearchUsingQueue())
            ->onConnection($models->first()->syncWithSearchUsing()));
    }
}
