@extends('admin.template')

@section('content')
<div class="row clearfix">
    <div class="col-sm-7">
        <div class="card">
            <div class="header">
                <h2>
                    {{ $title }}
                    <a href="{{ url('admin/email/list') }}" title="Email List" class="pull-right btn btn-xs btn-primary waves-effect"><i class="material-icons">list</i></a>
                </h2>
            </div>


            <div class="body"> 
                {!! Form::open(['url' => 'admin/email/bulk', 'class' => 'form-validation frmValidation', 'files' => true]) !!}

                <div class="row">
                    <div class="col-sm-12"> 
                        <label for="email">{{ Lang::label('Email') }} *</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('email') ? 'error focused' : '' }}">
                                <input data-role="tagsinput" name="email" type="text" id="email" class="form-control" placeholder="{{ Lang::label('Enter Receiver Email Address') }}" value="{{ old('email') }}" /> 
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

    <div class="col-sm-5">
        <div class="card">
            <div class="header">
                <h2>
                    {{ Lang::label("Client List") }}
                    <a href="{{ url('admin/client/new') }}" title="New Client " class="pull-right btn btn-xs btn-primary waves-effect"><i class="material-icons">add</i></a>
                </h2>
                <span class="text-danger">N.b: Click on the contact to send email</span>
            </div> 

            <div class="body"> 
                <table class="table table-condensed table-striped dataTable-client">
                    <thead>
                        <tr> 
                            <th>{{ Lang::label('Name') }}</th>
                            <th>{{ Lang::label('Contact') }}</th> 
                        </tr>
                    </thead> 
                    <tfoot>
                        <tr> 
                            <th>{{ Lang::label('Name') }}</th>
                            <th>{{ Lang::label('Contact') }}</th> 
                        </tr>
                    </tfoot>  
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
    $('.dataTable-client').DataTable({
        // dom: 'frtip',
        dom: "<'row'<'col-sm-12'f>>tp",
        responsive: true,
        pageLength: 6, // default records per page 
        processing: true,
        serverSide: true,
        pagingType: "full_numbers",
        ajax: "{{ url('client/contact/data') }}",
        columns: [ 
            {data: 'name',   name: 'name'},
            {data: 'contact',  name: 'contact'} 
        ],
    });


    $('.bootstrap-tagsinput').css({'width':'100%','height':'120px'});
    $('.bootstrap-tagsinput input').css({'width':'100%','height':'100%'});
    $('body').on('click', 'table>tbody>tr', function(){
        var emails = $('.bootstrap-tagsinput input').val();
        $('.bootstrap-tagsinput input').val(
            emails+','+$(this)
            .find('email')
            .text()
        );
    }); 

});
</script>
@endsection