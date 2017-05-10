@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="login-page">
    <div class="row">
        {{-- <div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3"> --}}
        <div class="login-form">
            <p class="text-center lead">
                Selamat datang di aplikasi <br><strong>{{ config('app.name') }}</strong>
            </p>
            <p class="text-center">Silakan login untuk melanjutkan aktifitas Anda.</p>
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title text-center">Login</strong></h3></div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">Username</label>

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
                            <label for="password" class="col-md-4 control-label">Password</label>

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
                                <button type="submit" class="btn btn-primary btn-block">
                                    Login
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