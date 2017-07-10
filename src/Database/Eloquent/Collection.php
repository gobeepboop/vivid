<?php

namespace Beep\Vivid\Database\Eloquent;

class Collection extends \Illuminate\Database\Eloquent\Collection
{
    /**
     * Get the array of primary keys.
     *
     * @return array
     */
    final public function modelKeys()
    {
        return array_map(function ($model) {
            return $model->getQueueableId();
        }, $this->items);
    }
}
