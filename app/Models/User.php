<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Policies\UserPolicy;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[UsePolicy(UserPolicy::class)]
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    ///////////////////////////////////////////////////////////////////////////
    public function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

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
    public function scopeFilterBy(Builder $query, string $search): void {
        $search = "%{$search}%";

        $query->where('name',  'LIKE', $search)
            ->orWhere('email', 'LIKE', $search);
    }

}
