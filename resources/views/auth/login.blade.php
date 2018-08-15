@extends('layouts.guest')

@section('title', __('auth.login'))

@section('content')
<div class="login-page">
    <div class="row">
        <div class="login-form">
            <div class="text-center lead">
                {{ Html::image('imgs/logo.png', env('STORE_NAME'), ['style' => 'width:100px']) }}
            </div>
            <p class="text-center lead">
                {!! __('auth.login_welcome', ['app_name' => env('STORE_NAME')]) !!}
            </p>
            <p class="text-center">{{ __('auth.login_notes') }}</p>
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title text-center">{{ __('auth.login') }}</h3></div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">{{ __('auth.username') }}</label>

                            <div class="col-md-8">
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>
                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">{{ __('auth.password') }}</label>

                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-success btn-block">
                                    {{ __('auth.login') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('ext_css')
{{ Html::style('css/login.css') }}
@endsection
