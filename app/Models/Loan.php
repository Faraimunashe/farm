<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'lender_id',
        'farm_id',
        'amount',
        'purpose',
        'status',
        'interest_rate',
        'term_months',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lender()
    {
        return $this->belongsTo(Lender::class);
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
