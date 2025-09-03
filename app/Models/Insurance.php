<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $fillable = [
        'user_id',
        'insurer_id',
        'insurance_plan_id',
        'farm_id',
        'policy_number',
        'coverage_amount',
        'premium_amount',
        'coverage_type',
        'start_date',
        'end_date',
        'status',
        'payment_status',
        'payment_method',
        'transaction_id',
        'renewal_date',
        'claims_count',
        'last_claim_date',
    ];

    protected $casts = [
        'coverage_amount' => 'decimal:2',
        'premium_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'renewal_date' => 'date',
        'last_claim_date' => 'date',
        'claims_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function insurer()
    {
        return $this->belongsTo(Insurer::class);
    }

    public function insurancePlan()
    {
        return $this->belongsTo(InsurancePlan::class);
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', now());
    }
}
