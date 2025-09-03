<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insurer extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'contact',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function insurancePlans()
    {
        return $this->hasMany(InsurancePlan::class);
    }

    public function activeInsurancePlans()
    {
        return $this->hasMany(InsurancePlan::class)->where('is_active', true);
    }

    public function insurances()
    {
        return $this->hasMany(Insurance::class);
    }

    public function activeInsurances()
    {
        return $this->hasMany(Insurance::class)->where('status', 'active');
    }
}
