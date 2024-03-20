<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Some of the models have an active state, i.e.
 * they can be hidden from the public eye using
 * their is_active column.
 * This trait is used to register a local scope
 * on all such models which is then used to filter
 * out inactive records, e.g.:
 * 
 *     Book::active()->first()
 */

trait HasActiveState
{

    ///////////////////////////////////////////////////////////////////////////
    public function scopeActive(Builder $query): void  {
        $query->where('is_active', true);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * A property wrapper method to check if the current
     * object is marked as active.
     * 
     * @return bool
     */
    public function isActive(): bool {
        return $this->is_active;
    }

}