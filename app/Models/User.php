<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    ///////////////////////////////////////////////////////////////////////////
    /**
     * When the user resets their password, their remember token
     * should also be updated, and a password reset event should
     * be triggered.
     * 
     * NB! The password is passed as a raw string, but it
     *     gets automatically hashed as it is declared as
     *     a hashed cast field.
     * 
     * @see \App\Admin\Http\Requests\Auth\ResetPasswordRequest
     * 
     * @param  string $password – new password
     * @return void
     */
    public function resetPassword(string $password): void {
        $this
            ->forceFill(['password' => $password])
            ->setRememberToken(Str::random(60));

        $this->save();

        event(new PasswordReset($this));
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get a list of collection of all administrators.
     * 
     * NB! Keep in mind that the application does not support regular
     *     users' registrations, so for all purposes a regular user
     *     is considered an administrator.
     * 
     * @return Collection<User>
     */
    public static function getAllAdministrators(): Collection {
        return self::all();
    }

    ///////////////////////////////////////////////////////////////////////////
    public function scopeFilterBy(Builder $query, string $search): void {
        $search = "%{$search}%";

        $query->where('name',  'LIKE', $search)
            ->orWhere('email', 'LIKE', $search);
    }

}
