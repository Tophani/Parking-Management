@extends('operator.template')

@section('content')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    {{ $title }} 
                </h2>
            </div>


            <div class="body">
                {!! Form::open(['url' => 'operator/setting/profile', 'class' => 'form-validation frmValidation', 'files' => true]) !!}

                    {!! Form::hidden('id', $user->id) !!}

                    <label for="name">{{ Lang::label('Name') }}</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('name') ? 'error focused' : '' }}">
                            <input name="name" type="text" id="name" class="form-control" placeholder="{{ Lang::label('Name') }}" value="{{ $user->name }}">
                        </div>
                        @if ($errors->has('name'))
                            <label class="error">{{ $errors->first('name') }}</label>
                        @endif
                    </div>

                    <label for="email">{{ Lang::label('Email') }}</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('email') ? 'error focused' : '' }}">
                            <input name="email" type="text" id="email" class="form-control" placeholder="{{ Lang::label('Email') }}" value="{{ $user->email }}">
                        </div>
                        @if ($errors->has('email'))
                            <label class="error">{{ $errors->first('email') }}</label>
                        @endif
                    </div>

                    <label for="password">{{ Lang::label('Password') }}</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('password') ? 'error focused' : '' }}">
                            <input name="password" type="password" id="password" class="form-control" placeholder="{{ Lang::label('Password') }}">
                        </div>
                        @if ($errors->has('password'))
                            <label class="error">{{ $errors->first('password') }}</label>
                        @endif
                    </div>


                    <label for="conf_password">{{ Lang::label('Confirm Password') }}</label>
                    <div class="form-group">
                        <div class="form-line {{ $errors->has('conf_password') ? 'error focused' : '' }}">
                            <input name="conf_password" type="password" id="conf_password" class="form-control" placeholder="{{ Lang::label('Confirm Password') }}">
                        </div>
                        @if ($errors->has('conf_password'))
                            <label class="error">{{ $errors->first('conf_password') }}</label>
                        @endif
                    </div>

                    <label for="photo">{{ Lang::label('Photo') }}</label>
                    <div class="form-group"> 
                        <div class="form-line  {{ $errors->has('photo') ? 'error focused' : '' }}">
                        <div class="row">
                            <div class="col-sm-3">
                                <img src="{{ asset(old('photo')?old('photo'):$user->photo) }}" width="150" height="100" class="img-thumbnail">
                            </div>

                            <div class="col-sm-9">
                                <input name="photo" type="file" id="photo" class="form-control">
                                <input name="old_photo" type="hidden" value="{{ $user->photo }}">
                            </div>
                        </div>
                        </div>
                        @if ($errors->has('photo'))
                            <label class="error">{{ $errors->first('photo') }}</label>
                        @endif
                    </div>

                    
                    <button type="reset" class="btn btn-danger waves-effect">{{ Lang::label('Reset') }}</button>
                    <button type="submit" class="btn btn-success waves-effect">{{ Lang::label('Update') }}</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
