<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    protected $fillable = [
        'user_id', 'organization_id', 'scan_type', 'status',
        'target', 'results', 'credits_used', 'risk_score',
        'ai_summary', 'completed_at',
    ];

    protected $casts = [
        'results'      => 'array',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
