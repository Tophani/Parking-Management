@extends('auth.template')

@section('content')

{!! Form::open(['route' => 'login', 'id' => 'login', 'class'=> 'form-horizontal']) !!}
    
    <div class="msg">{{ Lang::label('Log in to start your session') }}</div>

    <div class="input-group">
        <span class="input-group-addon">
            <i class="material-icons">email</i>
        </span>
        <div class="form-line  {{ $errors->has('email') ? 'error focused' : '' }}">
            <input type="email" class="form-control" name="email" placeholder="Email" required autofocus>
        </div>
        @if ($errors->has('email'))
        <label class="error">{{ $errors->first('email') }}</label>
        @endif
    </div>
    <div class="input-group">
        <span class="input-group-addon">
            <i class="material-icons">lock</i>
        </span>
        <div class="form-line {{ $errors->has('password') ? 'error focused' : '' }}">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
            @if ($errors->has('password'))
                <label class="error">{{ $errors->first('password') }}</label>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-xs-8 p-t-5">
            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }} class="filled-in chk-col-pink"> 
            <label for="remember">{{ Lang::label("Remember Me") }}</label> 
        </div>
        <div class="col-xs-4">
            <button class="btn btn-block bg-pink waves-effect" type="submit">LOG IN</button>
        </div>
    </div>
<!--     <div class="row m-t-15 m-b--20">
        <div class="col-xs-12 align-right">
            <a href="{{ route('password.request') }}">{{ Lang::label("Forgot Your Password?") }}</a>
        </div>
    </div> -->
{!! Form::close() !!}
@endsection



