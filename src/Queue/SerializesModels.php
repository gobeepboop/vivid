<?php

namespace Beep\Vivid\Queue;

use Beep\Vivid\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Database\ModelIdentifier;

trait SerializesUuidModels
{
    use SerializesModels;

    /**
     * Get the restored property value after deserialization.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    final protected function getRestoredPropertyValue($value)
    {
        if (! $value instanceof ModelIdentifier) {
            return $value;
        }

        $model = new $value->class;

        return is_array($value->id)
            ? $this->restoreCollection($value)
            : $this->getQueryForModelRestoration($model)
                   ->useWritePdo()->findOrFail(
                    $model instanceof Model && $model->getOptimizedUuid() === true
                        ? Uuid::fromString($value->id)->getBytes() : $value->id
                );
    }
}
