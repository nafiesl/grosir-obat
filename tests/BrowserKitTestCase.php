<?php

namespace Tests;

use App\User;

abstract class BrowserKitTestCase extends \Laravel\BrowserKitTesting\TestCase
{
    use CreatesApplication;

    protected $baseUrl = 'http://localhost';

    protected function loginAsUser()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        return $user;
    }
}
