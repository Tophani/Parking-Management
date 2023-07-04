@extends('operator.template')

@section('content')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    {{ $title }}
                    <a href="{{ url('operator/sms/list') }}" title="SMS List" class="pull-right btn btn-xs btn-primary waves-effect"><i class="material-icons">list</i></a>
                </h2>
            </div>


            <div class="body">
                {!! Form::open(['url' => 'operator/sms/new', 'class' => 'form-validation frmValidation', 'files' => true]) !!}

                <div class="row">
                    <div class="col-sm-8"> 
                        <label for="to">{{ Lang::label('Send To') }} *</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('to') ? 'error focused' : '' }}">
                                <input name="to" type="text" id="to" class="form-control" placeholder="{{ Lang::label('Mobile No.') }}" value="{{ old('to') }}">
                            </div>
                            @if ($errors->has('to'))
                                <label class="error">{{ $errors->first('to') }}</label>
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
    $("input[name=to]")
    .autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: "{{ url('client/contact/mobile') }}",
                dataType: "json",
                data: {mobile: request.term},
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