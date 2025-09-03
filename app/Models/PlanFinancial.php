<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanFinancial extends Model
{
    protected $fillable = [
        'plan_id',
        'type',
        'description',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
