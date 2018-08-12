<?php

namespace Tests;

use App\User;
use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function loginAsUser()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        return $user;
    }

    protected function disableExceptionHandling()
    {
        // Disable Laravel's default exception handling
        // and allow exceptions to bubble up the stack
        $this->app->instance(ExceptionHandler::class, new class() extends Handler {
            public function __construct()
            {
            }

            public function report(\Exception $exception)
            {
            }

            public function render($request, \Exception $exception)
            {
                throw $exception;
            }
        });
    }
}
