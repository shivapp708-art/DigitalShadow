<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes;

    protected $fillable = [
        'phone', 'email', 'name', 'trust_tier', 'user_type', 'credits',
        'stripe_customer_id', 'phone_verified_at', 'email_verified_at',
    ];

    protected $hidden = ['remember_token'];

    protected $casts = [
        'phone_verified_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'is_active'         => 'boolean',
    ];

    public function kycProfile()
    {
        return $this->hasOne(KycProfile::class);
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_users')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function scans()
    {
        return $this->hasMany(Scan::class);
    }

    public function hasCredits(int $amount = 1): bool
    {
        return $this->credits >= $amount;
    }

    public function deductCredits(int $amount): void
    {
        $this->decrement('credits', $amount);
    }

    public function isTier(string $tier): bool
    {
        $tiers = ['guest' => 0, 'verified' => 1, 'kyc_lite' => 2, 'kyc3' => 3];
        return ($tiers[$this->trust_tier] ?? 0) >= ($tiers[$tier] ?? 0);
    }
}
