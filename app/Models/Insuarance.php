<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insuarance extends Model
{
    public function insurancePlan()
    {
        return $this->belongsTo(InsuarancePlan::class);
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
