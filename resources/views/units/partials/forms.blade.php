@if (! Request::has('action'))
{{ link_to_route('units.index', trans('unit.create'), ['action' => 'create'], ['class' => 'btn btn-success pull-right']) }}
@endif
@if (Request::get('action') == 'create')
    {!! Form::open(['route' => 'units.store']) !!}
    {!! FormField::text('name', ['label' => trans('unit.name'), 'required' => true]) !!}
    {!! Form::submit(trans('unit.create'), ['class' => 'btn btn-success']) !!}
    {{ link_to_route('units.index', trans('app.cancel'), [], ['class' => 'btn btn-default']) }}
    {!! Form::close() !!}
@endif
@if (Request::get('action') == 'edit' && $editableUnit)
    {!! Form::model($editableUnit, ['route' => ['units.update', $editableUnit->id],'method' => 'patch']) !!}
    {!! FormField::text('name', ['label' => trans('unit.name'), 'required' => true]) !!}
    {!! Form::submit(trans('unit.update'), ['class' => 'btn btn-success']) !!}
    {{ link_to_route('units.index', trans('app.cancel'), [], ['class' => 'btn btn-default']) }}
    {!! Form::close() !!}
@endif
@if (Request::get('action') == 'delete' && $editableUnit)
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">{{ trans('unit.delete') }}</h3></div>
        <div class="panel-body">
            <label class="control-label">{{ trans('unit.name') }}</label>
            <p>{{ $editableUnit->name }}</p>
            {!! $errors->first('unit_id', '<span class="form-error small">:message</span>') !!}
            <hr>
            {{ trans('unit.delete_confirm') }}
        </div>
        <div class="panel-footer">
            {!! FormField::delete(['route'=>['units.destroy',$editableUnit->id]], trans('app.delete_confirm_button'), ['class'=>'btn btn-danger'], ['unit_id'=>$editableUnit->id]) !!}
            {{ link_to_route('units.index', trans('app.cancel'), [], ['class' => 'btn btn-default']) }}
        </div>
    </div>
@endif