<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'domain', 'cin', 'gstin',
        'verification_method', 'domain_verified', 'domain_verified_at',
        'plan', 'credits', 'stripe_customer_id',
    ];

    protected $casts = [
        'domain_verified'    => 'boolean',
        'domain_verified_at' => 'datetime',
    ];

    public function members()
    {
        return $this->belongsToMany(User::class, 'organization_users')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function scans()
    {
        return $this->hasMany(Scan::class);
    }
}
