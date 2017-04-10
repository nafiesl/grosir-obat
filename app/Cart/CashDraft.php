<?php

namespace App\Cart;

/**
 * Cash Draft.
 */
class CashDraft extends TransactionDraft
{
    public $draftKey;
    public $type = 'cash';
    public $type_id = 1;
}
