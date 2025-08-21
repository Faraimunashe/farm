<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
