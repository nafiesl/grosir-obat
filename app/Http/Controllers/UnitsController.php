<?php

namespace App\Http\Controllers;

use App\Unit;
use Illuminate\Http\Request;

class UnitsController extends Controller
{
    public function index(Request $request)
    {
        $editableUnit = null;
        $units = Unit::withCount('products')->get();

        if (in_array($request->get('action'), ['edit', 'delete']) && $request->has('id')) {
            $editableUnit = Unit::find($request->get('id'));
        }

        return view('units.index', compact('units', 'editableUnit'));
    }

    public function store(Request $request)
    {
        $newUnit = $request->validate([
            'name' => 'required|max:20',
        ]);

        Unit::create($newUnit);

        flash(trans('unit.created'), 'success');

        return redirect()->route('units.index');
    }

    public function update(Request $request, Unit $unit)
    {
        $unitData = $request->validate([
            'name' => 'required|max:20',
        ]);

        $unit->update($unitData);

        flash(trans('unit.updated'), 'success');

        return redirect()->route('units.index');
    }

    public function destroy(Request $request, Unit $unit)
    {
        $this->validate($request, [
            'unit_id' => 'required|exists:product_units,id|not_exists:products,unit_id',
        ], [
            'unit_id.not_exists' => trans('unit.undeleteable'),
        ]);

        if ($request->get('unit_id') == $unit->id && $unit->delete()) {
            flash(trans('unit.deleted'), 'success');

            return redirect()->route('units.index');
        }

        flash(trans('unit.undeleted'), 'error');

        return back();
    }
}
