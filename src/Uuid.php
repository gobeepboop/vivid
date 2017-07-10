<?php

namespace Beep\Vivid;

use Ramsey\Uuid\Uuid as RamseyUuid;

/**
 * Class Uuid.
 */
trait Uuid
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function bootUuid(): void
    {
        static::creating(function ($model) {
            $model->setUuidAttribute($model->getKeyName());
        });
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Determines whether the Model is optimized.
     *
     * @return bool
     */
    public function getOptimizedUuid(): bool
    {
        return $this->optimizedUuid;
    }

    /**
     * Sets the id attribute to a binary.
     *
     * @param string $id
     */
    public function setIdAttribute(string $id): void
    {
        $this->setUuidAttribute('id', $id);
    }

    /**
     * Gets the uuid attribute.
     *
     * @return string
     */
    public function getUuidAttribute(): string
    {
        $id = $this->{$this->primaryKey};

        return $this->getOptimizedUuid() === true ? RamseyUuid::fromBytes($id)->toString() : $id;
    }

    /**
     * Get the primary key.
     *
     * @return mixed
     */
    public function getKey(): ?string
    {
        return $this->determineKey();
    }

    /**
     * Get the queueable identity for the entity.
     *
     * @return mixed
     */
    public function getQueueableId()
    {
        return $this->getOptimizedUuid() ? RamseyUuid::fromBytes($this->getKey())->toString() : $this->getKey();
    }

    /**
     * Transforms UUID4 strings for attribute setter methods.
     *
     * @param string $column
     * @param string $uuid
     */
    protected function setUuidAttribute(string $column, string $uuid = null): void
    {
        if (empty($uuid)) {
            $uuid = RamseyUuid::uuid4()->toString();
        }
        $this->attributes[$column] = $this->getOptimizedUuid() && RamseyUuid::isValid($uuid)
            ? RamseyUuid::fromString($uuid)->getBytes() : $uuid;
    }

    /**
     * Get bytes format by given UUID.
     *
     * @param string $uuid
     *
     * @return string
     */
    protected function uuidAsBytes(string $uuid): string
    {
        return RamseyUuid::isValid($uuid) === true ? RamseyUuid::fromString($uuid)->getBytes() : $uuid;
    }

    /**
     * Get the primary key value for a save query.
     *
     * @return mixed
     */
    protected function getKeyForSaveQuery()
    {
        return $this->determineKey();
    }

    /**
     * Guesses whether the given value is a UUID.
     *
     * @param  string $value
     *
     * @return bool
     */
    protected function guessIfOptimizedUuid($value): bool
    {
        return is_string($value) === true && strlen($value) === 16;
    }

    /**
     * Determines the key through a backtrace.
     *
     * @return string
     */
    protected function determineKey(): ?string
    {
        $key = array_key_exists($this->getKeyName(), $this->attributes) ? $this->attributes[$this->getKeyName()] : null;

        if (! $this->getOptimizedUuid() || $key === null) {
            return $key;
        }

        return $this->optimizableViaBacktrace() ? RamseyUuid::fromBytes($key)->toString() : $key;
    }

    /**
     * Determines whether optimizable via backtrace.
     *
     * @return bool
     */
    protected function optimizableViaBacktrace(): bool
    {
        $haystack   = collect(['\\Scout\\']);

        return collect(debug_backtrace())->take(5)->contains(function ($trace) use ($haystack): bool {
            return array_has($trace, 'class') === true && $haystack->contains(function (string $needle) use ($trace) {
                    return str_contains(array_get($trace, 'class'), $needle);
            });
        });
    }
}
