<?php

namespace App\Cart;

/**
 * Credit Draft.
 */
class CreditDraft extends TransactionDraft
{
    public $draftKey;
    public $type = 'credit';
    public $type_id = 2;
}
