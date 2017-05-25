@extends('layouts.app')

@section('title', trans('user.list'))

@section('content')
<h3 class="page-header">{{ trans('user.list') }}</h3>

<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default table-responsive">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th class="text-center">{{ trans('app.table_no') }}</th>
                        <th>{{ trans('user.name') }}</th>
                        <th>{{ trans('user.username') }}</th>
                        <th class="text-center">{{ trans('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                    <tr>
                        <td class="text-center">{{ 1 + $key }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td class="text-center">
                            {!! link_to_route('users.index', trans('app.edit'), ['action' => 'edit', 'id' => $user->id], ['id' => 'edit-user-' . $user->id]) !!} |
                            {!! link_to_route('users.index', trans('app.delete'), ['action' => 'delete', 'id' => $user->id], ['id' => 'del-user-' . $user->id]) !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4">@include('users.partials.forms')</div>
</div>
@endsection