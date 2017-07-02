<?php

namespace Beep\Vivid\Database\Eloquent;

use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid as RamseyUuid;

class Model extends \Illuminate\Database\Eloquent\Model
{
    use Uuid;

    /**
     * Indicates whether UUID4 attributes are optimized.
     *
     * @var bool
     */
    protected $optimizedUuid = true;

    /**
     * {@inheritdoc}
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, ['find', 'findOrFail', 'where', 'whereIn'])) {
            $parameters = Collection::make($parameters)->transform(function ($value) {
                if (! RamseyUuid::isValid($value) || ! $this->getOptimizedUuid()) {
                    return $value;
                }

                return $this->uuidAsBytes($value);
            })->toArray();
        }

        return parent::__call($method, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        $serialized = $this->toArray();

        if (! $this->getOptimizedUuid()) {
            return $serialized;
        }

        return Collection::make($serialized)->transform(function ($value) {
            if (! $this->guessIfOptimizedUuid($value)) {
                return $value;
            }

            try {
                $attempt = RamseyUuid::fromBytes($value)->toString();
            } catch (\InvalidArgumentException $exception) {
                return $value;
            }

            return $attempt;
        })->toArray();
    }
}
