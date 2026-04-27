<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KycProfile extends Model
{
    protected $fillable = [
        'user_id', 'method', 'status',
        'name_encrypted', 'dob_encrypted', 'gender_encrypted',
        'aadhaar_ref_id_hash', 'pan_hash', 'email_hash', 'phone_hash',
        'face_match_score', 'liveness_passed',
        'xml_generated_at', 'verified_at',
    ];

    protected $casts = [
        'xml_generated_at' => 'datetime',
        'verified_at'      => 'datetime',
        'liveness_passed'  => 'boolean',
        'face_match_score' => 'decimal:3',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
