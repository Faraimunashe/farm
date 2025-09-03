<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'contact',
        'size',
        'type',
    ];

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
        return $this->hasMany(Insurance::class);
    }

    public function activeInsurance()
    {
        return $this->hasOne(Insurance::class)->where('status', 'active');
    }

    public function pendingInsurance()
    {
        return $this->hasOne(Insurance::class)->where('status', 'pending');
    }
}
