@extends('operator.template')

@section('content')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    {{ $title }}
                    <a href="{{ url('operator/message/sent') }}" title="Sent Message" class="pull-right btn btn-xs btn-primary waves-effect"><i class="material-icons">list</i></a>
                </h2>
            </div>

            <div class="body">
                {!! Form::open(['url' => '/operator/message/new', 'class' => 'form-validation frmValidation', 'files' => true]) !!}

                    <label for="user_id">{{ Lang::label('Send To') }} *</label>
                    <div class="form-group">
                        <div class="form-line {{ $errors->has('user_id') ? 'error focused' : '' }}">
                            {{ Form::select('user_id', $userList,  old('user_id'), ['class'=>'form-control', 'id'=>'user_id', 'placeholder'=>'Select option']) }} 
                        </div>
                        @if ($errors->has('user_id'))
                            <label class="error">{{ $errors->first('user_id') }}</label>
                        @endif
                    </div>

                    <label for="subject">{{ Lang::label('Subject') }} *</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('subject') ? 'error focused' : '' }}">
                            <input name="subject" type="text" id="subject" class="form-control" placeholder="{{ Lang::label('Subject') }}" value="{{ old('subject') }}">
                        </div>
                        @if ($errors->has('subject'))
                            <label class="error">{{ $errors->first('subject') }}</label>
                        @endif
                    </div>
 
                    <label for="message">{{ Lang::label('Message') }} *</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('message') ? 'error focused' : '' }}">
                            <textarea name="message" type="text" id="message" class="form-control" placeholder="{{ Lang::label('Message') }}">{{ old('message') }}</textarea>
                        </div>
                        @if ($errors->has('message'))
                            <label class="error">{{ $errors->first('message') }}</label>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="form-line">
                            <button type="reset" class="btn btn-danger waves-effect">{{ Lang::label('Reset') }}</button>
                            <button type="submit" class="btn btn-success waves-effect">{{ Lang::label('Send') }}</button>
                        </div> 
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
