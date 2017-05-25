@if (! Request::has('action'))
{{ link_to_route('users.index', trans('user.create'), ['action' => 'create'], ['class' => 'btn btn-success pull-right']) }}
@endif
@if (Request::get('action') == 'create')
    {!! Form::open(['route' => 'users.store']) !!}
    {!! FormField::text('name', ['label' => trans('user.name'), 'required' => true]) !!}
    {!! FormField::text('username', ['label' => trans('user.username'), 'required' => true]) !!}
    {!! FormField::password('password', ['label' => trans('auth.password'), 'info' => ['text' => 'Kosongkan jika menggunakan password default: <strong>rahasia</strong>']]) !!}
    {!! Form::submit(trans('user.create'), ['class' => 'btn btn-success']) !!}
    {{ link_to_route('users.index', trans('app.cancel'), [], ['class' => 'btn btn-default']) }}
    {!! Form::close() !!}
@endif
@if (Request::get('action') == 'edit' && $editableUser)
    {!! Form::model($editableUser, ['route' => ['users.update', $editableUser->id],'method' => 'patch']) !!}
    {!! FormField::text('name', ['label' => trans('user.name'), 'required' => true]) !!}
    {!! FormField::text('username', ['label' => trans('user.username'), 'required' => true]) !!}
    {!! FormField::password('password', ['label' => trans('auth.password'), 'info' => ['text' => 'Isi untuk mengganti password.']]) !!}
    {!! Form::submit(trans('user.update'), ['class' => 'btn btn-success']) !!}
    {{ link_to_route('users.index', trans('app.cancel'), [], ['class' => 'btn btn-default']) }}
    {!! Form::close() !!}
@endif
@if (Request::get('action') == 'delete' && $editableUser)
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">{{ trans('user.delete') }}</h3></div>
        <div class="panel-body">
            <label class="control-label">{{ trans('user.name') }}</label>
            <p>{{ $editableUser->name }}</p>
            <p>{{ $editableUser->username }}</p>
            {!! $errors->first('user_id', '<span class="form-error small">:message</span>') !!}
            <hr>
            {{ trans('user.delete_confirm') }}
        </div>
        <div class="panel-footer">
            {!! FormField::delete(['route'=>['users.destroy',$editableUser->id]], trans('app.delete_confirm_button'), ['class'=>'btn btn-danger'], ['user_id'=>$editableUser->id]) !!}
            {{ link_to_route('users.index', trans('app.cancel'), [], ['class' => 'btn btn-default']) }}
        </div>
    </div>
@endif