<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Stats extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    public $fillable = [
        'total_impressions', 'total_likes',
    ];

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Most models that can be accessed through the application
     * by the end users are considered “statsable”, i.e. they have
     * a one-to-one polymorphic relationship to the Stats model
     * which in turn keeps track of statistical information.
     * It is also possible to get the main object through a
     * Stats object using the reversed morphTo relationship.
     * 
     * @return MorphTo
     */
    public function statsable(): MorphTo {
        return $this->morphTo('statsable');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Each stats record has a total_likes integer column,
     * but all individual likes can be loaded through the
     * likes HasMany relationship.
     * 
     * @return HasMany
     */
    public function likes(): HasMany {
        return $this->hasMany(Like::class);
    }

}
