<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function financials()
    {
        return $this->hasMany(PlanFinancial::class);
    }
}
