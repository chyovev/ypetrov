<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visitor extends Model
{
    use HasFactory;

    /**
     * Rename the default timestamp columns to
     * better describe their purpose, the parent
     * model takes care of the rest.
     * 
     * @var string
     */
    const CREATED_AT = 'first_visit_date',
          UPDATED_AT = 'last_visit_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'ip_hash', 'country_code',
    ];

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Anytime a visitor record gets created, the IP address of the user
     * gets stored as well as it might be needed by other models in order
     * for them to identify the author of the request (for instance,
     * a visitor should not be able to like the same element twice).
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

}
