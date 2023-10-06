<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
        'ip', 'country_code',
    ];

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Anytime a visitor record gets created, the IP address of the user
     * gets stored as well as it might be needed by other models in order
     * for them to identify the author of the request (for instance,
     * a visitor should not be able to like the same element twice).
     * Since the IP address should be hashed in order to comply with
     * GDPR regulations, it's more convenient to pass the raw IP address
     * to the ip() virtual attribute whose mutator takes care of the
     * hashing and subsequently passing the hashed value to the actual
     * database field. 
     * 
     * @return Attribute
     */
    ///////////////////////////////////////////////////////////////////////////
    protected function ip(): Attribute {
        return Attribute::make(
            set: fn (string $ip) => [
                'ip_hash' => $this->hashIp($ip),
            ],
        );
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * The algorithm used for the IP hashing is sha256 which
     * always returns a fixed value of 64 characters – even
     * if there is an insignificant chance for a collision,
     * the purpose of the hashing makes it worth the “risk”.
     * 
     * @param  string $ip
     * @return string
     */
    private function hashIp(string $ip): string {
        return hash('sha256', $ip);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * When creating a Visitor instance, the IP address of the
     * visitor is passed to the virtual attribute 'ip' whose
     * mutator takes care of hashing the value and using it
     * to populate the ip_hash field, thus leaving the ip_hash
     * field unexposed.
     * 
     * Similarly, when visitors get fetched from the database
     * by their IP addresses, one can use this query scope
     * which hashes a raw IP address before passing it on
     * to the query. Mainly used during visitor registration.
     * 
     * @see \App\Http\Middleware\RegisterVisitor
     * 
     * @param  Builder $query – query builder
     * @param  string  $ip    – raw IP address
     * @return void
     */
    public function scopeHasIp(Builder $query, string $ip): void {
        $query->where('ip_hash', $this->hashIp($ip));
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * To update the last visit date of a visitor, simply call
     * the touchQuietly method of the parent class (quietly,
     * as to prevent it from firing potential future events).
     * 
     * @return bool
     */
    public function updateLastVisitDate(): bool {
        return $this->touchQuietly();
    }

}
