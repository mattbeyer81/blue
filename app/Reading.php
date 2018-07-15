<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reading extends Model
{

    protected $fillable = [
        'customer_id',
        'serial_number',
        'power_type',
        'mpxn',
        'read',
        'read_date'
    ];

    protected $casts = [
        'read' => 'array'
    ];

}
