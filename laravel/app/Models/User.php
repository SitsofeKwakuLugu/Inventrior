<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasUuid;

class User extends Authenticatable
{
    use Notifiable, HasUuid;

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'company_id',
        'role',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Simple role check for the current project (fallback to string stored in `role` column).
     * Supports passing a single role string or an array of roles.
     */
    public function hasRole($role): bool
    {
        if (is_array($role)) {
            return in_array($this->role, $role, true);
        }

        return $this->role === $role;
    }

    /**
     * Assign a single role to the user (simple fallback implementation).
     */
    public function assignRole(string $role): bool
    {
        $this->role = $role;
        return (bool) $this->save();
    }

    /**
     * Quick email verification check (fallback if the MustVerifyEmail interface isn't used).
     */
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }
}