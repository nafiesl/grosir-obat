@extends('layouts.app')

@section('title', trans('unit.list'))

@section('content')
<h3 class="page-header">{{ trans('unit.list') }}</h3>

<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default table-responsive">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th class="text-center">{{ trans('app.table_no') }}</th>
                        <th>{{ trans('unit.name') }}</th>
                        <th class="text-center">{{ trans('unit.products_count') }}</th>
                        <th class="text-center">{{ trans('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($units as $key => $unit)
                    <tr>
                        <td class="text-center">{{ 1 + $key }}</td>
                        <td>{{ $unit->name }}</td>
                        <td class="text-center">{{ $unit->products_count }}</td>
                        <td class="text-center">
                            {!! link_to_route('units.index', trans('app.edit'), ['action' => 'edit', 'id' => $unit->id], ['id' => 'edit-unit-' . $unit->id]) !!} |
                            {!! link_to_route('units.index', trans('app.delete'), ['action' => 'delete', 'id' => $unit->id], ['id' => 'del-unit-' . $unit->id]) !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4">@include('units.partials.forms')</div>
</div>
@endsection