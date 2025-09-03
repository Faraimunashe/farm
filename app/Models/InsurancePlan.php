<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsurancePlan extends Model
{
    protected $fillable = [
        'insurer_id',
        'name',
        'description',
        'coverage_type',
        'coverage_percentage',
        'premium_rate',
        'min_coverage',
        'max_coverage',
        'duration_months',
        'deductible_amount',
        'is_active',
        'terms_conditions',
        'exclusions',
    ];

    protected $casts = [
        'coverage_percentage' => 'decimal:2',
        'premium_rate' => 'decimal:4',
        'min_coverage' => 'decimal:2',
        'max_coverage' => 'decimal:2',
        'deductible_amount' => 'decimal:2',
        'duration_months' => 'integer',
        'is_active' => 'boolean',
    ];

    public function insurer()
    {
        return $this->belongsTo(Insurer::class);
    }

    public function insurances()
    {
        return $this->hasMany(Insurance::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCoverageType($query, $type)
    {
        return $query->where('coverage_type', $type);
    }

    public function calculatePremium($coverageAmount)
    {
        return $coverageAmount * ($this->premium_rate / 100);
    }

    public function isEligibleForCoverage($coverageAmount)
    {
        return $coverageAmount >= $this->min_coverage && $coverageAmount <= $this->max_coverage;
    }
}
