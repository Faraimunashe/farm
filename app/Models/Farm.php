<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    public function creditScore()
    {
        return $this->hasOne(CreditScore::class);
    }

    public function insurances()
    {
        return $this->hasMany(Insuarance::class);
    }
}
