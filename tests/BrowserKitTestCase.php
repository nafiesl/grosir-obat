<?php

namespace Tests;

abstract class BrowserKitTestCase extends \Laravel\BrowserKitTesting\TestCase
{
    use CreatesApplication;

    protected $baseUrl = 'http://localhost';
}
