<?php

namespace Database\Factories\Traits;

use Illuminate\Database\Eloquent\Factories\Factory;

trait HasActiveState
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Indicate that the object should be marked as active.
     */
    public function active(): Factory {
        return $this->state(function () {
            return [
                'is_active' => true,
            ];
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Indicate that the object should be marked as inactive.
     */
    public function inactive(): Factory {
        return $this->state(function () {
            return [
                'is_active' => false,
            ];
        });
    }

}