<?php

namespace App\Cart;

/**
* Transaction Draft Interface
*/
abstract class TransactionDraft
{
    public function toArray()
    {
        return [
            'invoice_no' => 2,
            'date'       => 1,
            'items'      => [],
            'total'      => 0,
            'payment'    => 0,
            'customer'   => 0,
            'status_id'  => 0,
            'creator_id' => 0,
            'remark'     => '',
        ];
    }
}