<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'farm_id',
        'amount',
        'type',
        'description',
        'date',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
