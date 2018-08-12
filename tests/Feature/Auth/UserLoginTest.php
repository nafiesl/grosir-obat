<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserLoginTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_validates_the_login_form()
    {
        $this->visit(route('login'))
            ->type('foobar', 'username')
            ->type('secret', 'password')
            ->press('Login')
            ->dontSeeIsAuthenticated()
            ->seePageIs(route('login'));
        $this->see(trans('auth.failed'));
    }

    /** @test */
    public function user_can_login()
    {
        $user = factory(User::class)->create(['password' => '123456']);

        $this->visit(route('login'));
        $this->type($user->username, 'username');
        $this->type('123456', 'password');
        $this->press('Login');

        $this->seePageIs(route('home'));
        $this->see($user->name);

        // $this->dump();

        $this->press('logout-button');
        $this->seePageIs(route('login'));
    }

    /** @test */
    public function it_can_logout_of_the_application()
    {
        $user = factory(User::class)->create(['password' => '123456']);
        $this->actingAs($user)
             ->visit(route('home'))
             ->press('logout-button')
             ->seePageis(route('login'))
             ->dontSeeIsAuthenticated();
    }
}
