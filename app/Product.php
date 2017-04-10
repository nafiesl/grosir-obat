<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['cash_price', 'credit_price'];

    public function getPrice($type = 'cash')
    {
        if ($type == 'credit') {
            return $this->credit_price;
        }

        return $this->cash_price;
    }
}
