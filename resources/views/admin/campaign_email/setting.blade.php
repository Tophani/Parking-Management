@extends('admin.template')

@section('content')
<div class="clearfix">
    <div class="card">
        <div class="header">
            <h2>
                {{ $title }}
            </h2>
        </div>

        <div class="body">
            {!! Form::open(['url' => 'admin/email/setting', 'class' => 'form-validation']) !!}

                {!! Form::hidden('id', $setting->id) !!}
                
                <label for="driver">{{ Lang::label('Mail Driver') }} *</label>
                <div class="form-group">
                    <div class="form-line  {{ $errors->has('driver') ? 'error focused' : '' }}">
                        <input name="driver" type="text" id="driver" class="form-control" placeholder="{{ Lang::label('Mail Driver') }}" value="{{ $setting->driver }}">
                    </div>
                    @if ($errors->has('driver'))
                        <label class="error">{{ $errors->first('driver') }}</label>
                    @endif
                </div>
  
                <label for="host">{{ Lang::label('Mail Host') }} *</label>
                <div class="form-group">
                    <div class="form-line  {{ $errors->has('host') ? 'error focused' : '' }}">
                        <input name="host" type="text" id="host" class="form-control" placeholder="{{ Lang::label('Mail Host') }}" value="{{ $setting->host }}">
                    </div>
                    @if ($errors->has('host'))
                        <label class="error">{{ $errors->first('host') }}</label>
                    @endif
                </div>

                <label for="port">{{ Lang::label('Mail Port') }} *</label>
                <div class="form-group">
                    <div class="form-line  {{ $errors->has('port') ? 'error focused' : '' }}">
                        <input name="port" type="text" id="port" class="form-control" placeholder="{{ Lang::label('Mail Port') }}" value="{{ $setting->port }}">
                    </div>
                    @if ($errors->has('port'))
                        <label class="error">{{ $errors->first('port') }}</label>
                    @endif
                </div>
  
                <label for="username">{{ Lang::label('Username') }} *</label>
                <div class="form-group">
                    <div class="form-line  {{ $errors->has('username') ? 'error focused' : '' }}">
                        <input name="username" type="text" id="username" class="form-control" placeholder="{{ Lang::label('Username') }}" value="{{ $setting->username }}">
                    </div>
                    @if ($errors->has('username'))
                        <label class="error">{{ $errors->first('username') }}</label>
                    @endif
                </div>
  
                <label for="password">{{ Lang::label('Password') }} *</label>
                <div class="form-group">
                    <div class="form-line  {{ $errors->has('password') ? 'error focused' : '' }}">
                        <input name="password" type="password" id="password" class="form-control" placeholder="{{ Lang::label('Password') }}" value="{{ $setting->password }}">
                    </div>
                    @if ($errors->has('password'))
                        <label class="error">{{ $errors->first('password') }}</label>
                    @endif
                </div>
  
                <label for="encryption">{{ Lang::label('encryption') }} *</label>
                <div class="form-group">
                    <div class="form-line  {{ $errors->has('encryption') ? 'error focused' : '' }}">
                        <input name="encryption" type="text" id="encryption" class="form-control" placeholder="{{ Lang::label('encryption') }}" value="{{ $setting->encryption }}">
                    </div>
                    @if ($errors->has('encryption'))
                        <label class="error">{{ $errors->first('encryption') }}</label>
                    @endif
                </div>
  
                <label for="sendmail">{{ Lang::label('Sendmail') }} *</label>
                <div class="form-group">
                    <div class="form-line  {{ $errors->has('sendmail') ? 'error focused' : '' }}">
                        <input name="sendmail" type="text" id="sendmail" class="form-control" placeholder="{{ Lang::label('Sendmail') }}" value="{{ $setting->sendmail }}">
                    </div>
                    @if ($errors->has('sendmail'))
                        <label class="error">{{ $errors->first('sendmail') }}</label>
                    @endif
                </div>

                <label for="pretend">{{ Lang::label('Pretend') }} *</label>
                <div class="form-group">
                    <div class="form-line  {{ $errors->has('pretend') ? 'error focused' : '' }}">
                        <input name="pretend" type="text" id="pretend" class="form-control" placeholder="{{ Lang::label('Pretend') }}" value="{{ $setting->pretend }}">
                    </div>
                    @if ($errors->has('pretend'))
                        <label class="error">{{ $errors->first('pretend') }}</label>
                    @endif
                </div>

                <button type="reset" class="btn btn-danger waves-effect">{{ Lang::label('Reset') }}</button>
                <button type="submit" class="btn btn-success waves-effect">{{ Lang::label('Update') }}</button>
            {!! Form::close() !!}
        </div>
    </div> 
</div> 
@endsection
