<?php

namespace Tests\Feature;

use App\Unit;
use App\Product;
use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ManageUnitsTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_see_unit_list()
    {
        $unit1 = factory(Unit::class)->create(['name' => 'Testing 123']);
        $unit2 = factory(Unit::class)->create(['name' => 'Testing 456']);

        $this->loginAsUser();
        $this->visit(route('units.index'));
        $this->see($unit1->name);
        $this->see($unit2->name);
    }

    /** @test */
    public function user_can_create_a_unit()
    {
        $this->loginAsUser();
        $this->visit(route('units.index'));

        $this->click(trans('unit.create'));
        $this->seePageIs(route('units.index', ['action' => 'create']));

        $this->type('Unit 1', 'name');
        $this->press(trans('unit.create'));

        $this->seePageIs(route('units.index'));
        // $this->see(trans('unit.created'));

        $this->seeInDatabase('product_units', [
            'name' => 'Unit 1',
        ]);
    }

    /** @test */
    public function user_can_edit_a_unit()
    {
        $this->loginAsUser();
        $unit = factory(Unit::class)->create();

        $this->visit(route('units.index'));
        $this->click('edit-unit-'.$unit->id);
        $this->seePageIs(route('units.index', ['action' => 'edit', 'id' => $unit->id]));

        $this->type('Unit 1', 'name');
        $this->press(trans('unit.update'));

        // $this->see(trans('unit.updated'));
        $this->seePageIs(route('units.index'));

        $this->seeInDatabase('product_units', [
            'name' => 'Unit 1',
        ]);
    }

    /** @test */
    public function user_can_delete_a_unit()
    {
        $this->loginAsUser();
        $unit = factory(Unit::class)->create();

        $this->visit(route('units.index'));
        $this->click('del-unit-'.$unit->id);
        $this->seePageIs(route('units.index', ['action' => 'delete', 'id' => $unit->id]));

        $this->seeInDatabase('product_units', [
            'id' => $unit->id,
        ]);

        $this->press(trans('app.delete_confirm_button'));

        $this->dontSeeInDatabase('product_units', [
            'id' => $unit->id,
        ]);
    }

    /** @test */
    public function user_can_not_delete_a_unit_that_has_product()
    {
        $this->loginAsUser();
        $product = factory(Product::class)->create();
        $unitId = $product->unit_id;

        $this->visit(route('units.index'));
        $this->click('del-unit-'.$unitId);
        $this->seePageIs(route('units.index', ['action' => 'delete', 'id' => $unitId]));

        $this->press(trans('app.delete_confirm_button'));

        $this->see(trans('unit.undeleteable'));
        $this->seePageIs(route('units.index', ['action' => 'delete', 'id' => $unitId]));

        $this->seeInDatabase('product_units', [
            'id' => $unitId,
        ]);
    }
}
