<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['id', 'name', 'cash_price', 'credit_price', 'unit_id'];

    public function getPrice($type = 'cash')
    {
        if ($type == 'credit' && $this->credit_price) {
            return $this->credit_price;
        }

        return $this->cash_price;
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
