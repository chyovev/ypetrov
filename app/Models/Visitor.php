<?php

namespace App\Models;

use App\Casts\Sha256Cast;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['ip_hash', 'country_code', 'is_banned'])]
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

    ///////////////////////////////////////////////////////////////////////////
    /**
     * For anonimity reasons the IP address of a visitor gets hashed.
     * Even though Laravel supports several hashing options for casting
     * out-of-the-box, they all generate new outputs on each inbound
     * cast, even if the input is the same.
     * This may be handy for passwords, but in this case visitor get
     * fetched from the database by their hashed IP addresses during
     * the registration phase, so the value should always be fixed.
     * 
     * @see \App\Http\Middleware\RegisterVisitor
     */
    public function casts(): array {
        return [
            'is_banned' => 'boolean',
            'ip_hash'   => Sha256Cast::class,
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Mark a visitor as banned.
     * 
     * @return bool
     */
    public function markAsBanned(): bool {
        return $this->update(['is_banned' => true]);
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
