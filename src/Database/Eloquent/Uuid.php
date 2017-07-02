<?php

namespace Beep\Vivid\Database\Eloquent;

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
     * Get the primary key.
     *
     * @return mixed
     */
    public function getKey(): ?string
    {
        return array_key_exists($this->getKeyName(), $this->attributes) ? $this->attributes[$this->getKeyName()] : null;
    }

    /**
     * Get the queueable identity for the entity.
     *
     * @return mixed
     */
    public function getQueueableId()
    {
        return $this->getOptimizedUuid() ? $this->uuidAsBytes($this->getKey()) : $this->getKey();
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
        return array_key_exists($this->getKeyName(), $this->attributes) ? $this->attributes[$this->getKeyName()] : null;
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
}
