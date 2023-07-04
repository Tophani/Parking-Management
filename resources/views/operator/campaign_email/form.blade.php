@extends('operator.template')

@section('content')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    {{ $title }}
                    <a href="{{ url('operator/email/list') }}" title="Email List" class="pull-right btn btn-xs btn-primary waves-effect"><i class="material-icons">list</i></a>
                </h2>
            </div>


            <div class="body">
                {!! Form::open(['url' => 'operator/email/new', 'class' => 'form-validation frmValidation', 'files' => true]) !!}

                {{ Form::hidden('email_setting_id', (!empty($email_setting->id)?$email_setting->id:1)) }}
                <div class="row">
                    <div class="col-sm-8"> 
                        <label for="email">{{ Lang::label('Email') }} *</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('email') ? 'error focused' : '' }}">
                                <input name="email" type="text" id="email" class="form-control" placeholder="{{ Lang::label('Enter Receiver Email Address') }}" value="{{ old('email') }}">
                            </div>
                            @if ($errors->has('email'))
                                <label class="error">{{ $errors->first('email') }}</label>
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

                        <label for="message">{{ Lang::label('Message') }}</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('message') ? 'error focused' : '' }}">
                                <textarea name="message" type="text" id="message" class="form-control" rows="6" placeholder="{{ Lang::label('Message') }}">{{ old('message') }}</textarea>
                            </div>
                            @if ($errors->has('message'))
                                <label class="error">{{ $errors->first('message') }}</label>
                            @endif
                        </div>

                        <div class="form-group">
                            <button type="reset" class="btn btn-danger waves-effect">{{ Lang::label('Reset') }}</button>
                            <button type="submit" class="btn btn-success waves-effect">{{ Lang::label('Send') }}</button>
                        </div>
                    </div> 
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function(){ 
    $("input[name=email]")
    .autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: "{{ url('client/contact/email') }}",
                dataType: "json",
                data: {email: request.term},
                success: function( data ) {
                    response( data );
                },
                select: function (event, ui) {
                    return false;
                }
            });
        } 
    });  
})
</script>
@endsection