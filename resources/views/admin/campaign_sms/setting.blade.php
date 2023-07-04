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
            {!! Form::open(['url' => 'admin/sms/setting', 'class' => 'form-validation']) !!}

                {!! Form::hidden('id', $setting->id) !!}
                
                <label for="provider">{{ Lang::label('Provider') }} *</label>
                <div class="form-group">
                    <div class="form-line  {{ $errors->has('provider') ? 'error focused' : '' }}">
                        {{ Form::select('provider', ["nexmo"=>"Nexmo", "clickatell"=>"Click A Tell", "robi"=>"Robi","budgetsms"=>"Budget SMS"], $setting->provider, ["class"=>"form-control", "id"=>"provider"]) }}
                    </div>
                    @if ($errors->has('provider'))
                        <label class="error">{{ $errors->first('provider') }}</label>
                    @endif
                </div>
  
                <label for="api_key">{{ Lang::label('API Key') }} *</label>
                <div class="form-group">
                    <div class="form-line  {{ $errors->has('api_key') ? 'error focused' : '' }}">
                        <input name="api_key" type="text" id="api_key" class="form-control" placeholder="{{ Lang::label('API Key') }}" value="{{ $setting->api_key }}">
                    </div>
                    @if ($errors->has('api_key'))
                        <label class="error">{{ $errors->first('api_key') }}</label>
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
  
                <label for="from">{{ Lang::label('from') }} *</label>
                <div class="form-group">
                    <div class="form-line  {{ $errors->has('from') ? 'error focused' : '' }}">
                        <input name="from" type="text" id="from" class="form-control" placeholder="{{ Lang::label('Mobile No.') }}" value="{{ $setting->from }}">
                    </div>
                    @if ($errors->has('from'))
                        <label class="error">{{ $errors->first('from') }}</label>
                    @endif
                </div>

                <button type="reset" class="btn btn-danger waves-effect">{{ Lang::label('Reset') }}</button>
                <button type="submit" class="btn btn-success waves-effect">{{ Lang::label('Update') }}</button>
            {!! Form::close() !!}
        </div>
    </div> 
</div> 
@endsection
