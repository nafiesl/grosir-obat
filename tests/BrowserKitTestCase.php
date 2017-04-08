<?php

namespace Tests;

use App\Entities\Customers\Customer;
use App\Entities\Invoices\Invoice;
use App\Entities\Receipts\Receipt;
use App\Entities\Users\User;

abstract class BrowserKitTestCase extends \Laravel\BrowserKitTesting\TestCase
{
    use CreatesApplication;

    protected $baseUrl = 'http://localhost';
}
