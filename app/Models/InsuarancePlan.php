<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuarancePlan extends Model
{
    public function insurer()
    {
        return $this->belongsTo(Insurer::class);
    }

    public function insurances()
    {
        return $this->hasMany(Insuarance::class, 'insurance_plan_id');
    }
}
