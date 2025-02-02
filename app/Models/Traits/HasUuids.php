<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait HasUuids
{
    /**
     * Boot the HasUuids trait for a model.
     */
    protected static function bootHasUuids()
    {
        static::creating(function ($model) {
            if ($model->getKey() === null) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the key type for the model.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';  // Menggunakan UUID sebagai string
    }

    /**
     * Indicate that the IDs are not incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;  // UUID bukan tipe auto increment
    }
}
