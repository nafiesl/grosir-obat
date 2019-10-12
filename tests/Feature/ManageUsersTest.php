<?php

namespace Tests\Feature;

use App\User;
use App\Transaction;
use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ManageUsersTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_see_user_list()
    {
        $user1 = factory(User::class)->create(['name' => 'Testing 123']);
        $user2 = factory(User::class)->create(['name' => 'Testing 456']);

        $this->loginAsUser();
        $this->visit(route('users.index'));
        $this->see($user1->name);
        $this->see($user2->name);
    }

    /** @test */
    public function user_can_create_a_user()
    {
        $this->loginAsUser();
        $this->visit(route('users.index'));

        $this->click(trans('user.create'));
        $this->seePageIs(route('users.index', ['action' => 'create']));

        $this->type('User 1', 'name');
        $this->type('username', 'username');
        $this->type('rahasia', 'password');
        $this->press(trans('user.create'));

        // $this->see(trans('user.created'));
        $this->seePageIs(route('users.index'));

        $this->seeInDatabase('users', [
            'name'     => 'User 1',
            'username' => 'username',
        ]);
    }

    /** @test */
    public function user_can_edit_a_user()
    {
        $this->loginAsUser();
        $user = factory(User::class)->create();

        $this->visit(route('users.index'));
        $this->click('edit-user-'.$user->id);
        $this->seePageIs(route('users.index', ['action' => 'edit', 'id' => $user->id]));

        $this->type('User 1', 'name');
        $this->type('username', 'username');
        $this->press(trans('user.update'));

        // $this->see(trans('user.updated'));
        $this->seePageIs(route('users.index'));

        $this->seeInDatabase('users', [
            'id'       => $user->id,
            'name'     => 'User 1',
            'username' => 'username',
        ]);
    }

    /** @test */
    public function user_can_delete_a_user()
    {
        $this->loginAsUser();
        $user = factory(User::class)->create();

        $this->visit(route('users.index'));
        $this->click('del-user-'.$user->id);
        $this->seePageIs(route('users.index', ['action' => 'delete', 'id' => $user->id]));

        $this->seeInDatabase('users', [
            'id' => $user->id,
        ]);

        $this->press(trans('app.delete_confirm_button'));

        $this->dontSeeInDatabase('users', [
            'id' => $user->id,
        ]);
    }

    /** @test */
    public function user_can_not_delete_a_user_that_has_product()
    {
        $this->loginAsUser();
        $product = factory(Transaction::class)->create();
        $userId = $product->user_id;

        $this->visit(route('users.index'));
        $this->click('del-user-'.$userId);
        $this->seePageIs(route('users.index', ['action' => 'delete', 'id' => $userId]));

        $this->press(trans('app.delete_confirm_button'));

        $this->see(trans('user.undeleted'));
        $this->seePageIs(route('users.index', ['action' => 'delete', 'id' => $userId]));

        $this->seeInDatabase('users', [
            'id' => $userId,
        ]);
    }
}
