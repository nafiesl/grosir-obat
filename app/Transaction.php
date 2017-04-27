<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $casts = [
        'items'    => 'array',
        'customer' => 'array',
    ];
}
