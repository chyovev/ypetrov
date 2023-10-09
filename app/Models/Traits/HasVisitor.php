<?php

namespace App\Models\Traits;

use App\Models\Visitor;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Interactive models such as ContactMessage, Comment, Like,
 * etc. should be associated with a Visitor object in order
 * to be identifiable.
 */

trait HasVisitor
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Each interactive model belongs to a single Visitor.
     * 
     * @return BelongsTo
     */
    public function visitor(): BelongsTo {
        return $this->belongsTo(Visitor::class);
    }

}