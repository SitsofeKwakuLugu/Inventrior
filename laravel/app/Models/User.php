<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUuid;

class User extends Authenticatable
{
    use Notifiable, HasUuid, SoftDeletes;

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

    /**
     * Mark email as verified
     */
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Get the email address that should be used for verification
     */
    public function getEmailForVerification()
    {
        return $this->email;
    }

    /**
     * Send the email verification notification
     */
    public function sendEmailVerificationNotification()
    {
        // Create verification URL
        $verificationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify',
            now()->addHours(24),
            [
                'id' => $this->getKey(),
                'hash' => sha1($this->getEmailForVerification()),
            ]
        );

        // Send email (using simple mail for now)
        \Illuminate\Support\Facades\Mail::send('emails.verify-email', [
            'user' => $this,
            'verificationUrl' => $verificationUrl,
        ], function ($message) {
            $message->to($this->email)->subject('Verify Your Email Address');
        });
    }
}