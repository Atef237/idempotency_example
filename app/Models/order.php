<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    //

    protected $fillable = [
        'total_amount',
        'discunt_amount',
    ];
}
