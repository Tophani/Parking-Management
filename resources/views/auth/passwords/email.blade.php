@extends('auth.template')

@section('content')

<div class="msg">{{ Lang::label("Reset Password?") }}</div>

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

<form id="login" class="form-horizontal" method="POST" action="{{ route('password.email') }}">
    {{ csrf_field() }}

    <div class="input-group">
        <span class="input-group-addon">
            <i class="material-icons">email</i>
        </span>
        <div class="form-line  {{ $errors->has('email') ? 'error focused' : '' }}">
            <input type="email" class="form-control" name="email" placeholder="{{ Lang::label("Email") }}" required autofocus>
        </div>
        @if ($errors->has('email'))
        <label class="error">{{ $errors->first('email') }}</label>
        @endif
    </div> 

    <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit"> {{ Lang::label("Send Password Reset Link") }}</button>
    <div class="row m-t-20 m-b--5 align-center">
        <a href="{{ route('login') }}">{{ Lang::label("Login") }}</a>
    </div>

</form>
@endsection

 