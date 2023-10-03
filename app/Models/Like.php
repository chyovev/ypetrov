<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    public $fillable = [
        'ip_hash',
    ];
    
    ///////////////////////////////////////////////////////////////////////////
    /**
     * Anytime a like record gets created, the IP address of the user
     * gets stored as well in order to prevent the same element from
     * being liked multiple times and thus messing up the statistics.
     * To comply with GDPR regulations, the IP address gets hashed
     * automatically during mass assignment using the ipHash mutator.
     * The algorithm used is sha256 which always returns a value of
     * 64 characters – even if there is an insignificant chance for
     * a collision, the purpose of the hashing makes it worth the “risk”.
     * 
     * @return Attribute
     */
    protected function ipHash(): Attribute {
        return Attribute::make(
            set: fn (string $value) => hash('sha256', $value),
        );
    }

    ///////////////////////////////////////////////////////////////////////////
    public function stats(): BelongsTo {
        return $this->belongsTo(Stats::class);
    }
}
