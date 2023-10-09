<?php

namespace App\Models;

use App\Models\Traits\HasVisitor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    use HasFactory, HasVisitor;

    
    ///////////////////////////////////////////////////////////////////////////
    public function stats(): BelongsTo {
        return $this->belongsTo(Stats::class);
    }
}
