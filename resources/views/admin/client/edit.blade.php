@extends('admin.template')

@section('content')
<div class="row clearfix">
    <div class="col-sm-6">
        <div class="card">
            <div class="header">
                <h2>{{ $title }}</h2>
                <ul class="header-dropdown m-r--5">
                    <li>
                        <a href="{{ url('admin/client/new') }}" class="btn btn-sm btn-success waves-effect">
                            <i class="material-icons">add</i>
                            <span>{{ Lang::label('New Client') }}</span>
                        </a>
                    </li>  
                    <li>
                        <a href="{{ url('admin/client/list') }}" title="Client List" class="btn btn-sm btn-primary waves-effect">
                            <i class="material-icons">list</i>
                            <span>{{ Lang::label('Clients') }}</span>
                        </a> 
                    </li>  
                </ul>
            </div>  

            <div class="body">
                {!! Form::open(['url' => '/admin/client/edit']) !!}

                    {{ Form::hidden('id', $client->id) }}
                    <label for="name">{{ Lang::label('Name') }} *</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('name') ? 'error focused' : '' }}">
                            <input name="name" type="text" id="name" class="form-control" placeholder="{{ Lang::label('Enter name') }}" value="{{ (old('name')?old('name'):$client->name) }}">
                        </div>
                        @if ($errors->has('name'))
                            <label class="error">{{ $errors->first('name') }}</label>
                        @endif
                    </div>

                    <label for="mobile">{{ Lang::label('Phone / Mobile') }} *</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('mobile') ? 'error focused' : '' }}">
                            <input name="mobile" type="text" id="mobile" class="form-control" placeholder="{{ Lang::label('Enter Phone or Mobile No.') }}" value="{{ (old('mobile')?old('mobile'):$client->mobile) }}">
                        </div>
                        @if ($errors->has('mobile'))
                            <label class="error">{{ $errors->first('mobile') }}</label>
                        @endif
                    </div>

                    <label for="email">{{ Lang::label('Email') }} *</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('email') ? 'error focused' : '' }}">
                            <input name="email" type="text" id="email" class="form-control" placeholder="{{ Lang::label('Enter Email Address') }}" value="{{ (old('email')?old('email'):$client->email) }}">
                        </div>
                        @if ($errors->has('email'))
                            <label class="error">{{ $errors->first('email') }}</label>
                        @endif
                    </div>

                    <label for="password">{{ Lang::label('password') }} *</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('password') ? 'error focused' : '' }}">
                            <input name="password" type="password" id="password" class="form-control" placeholder="{{ Lang::label('password') }}" value="{{ old('password') }}">
                        </div>
                        @if ($errors->has('password'))
                            <label class="error">{{ $errors->first('password') }}</label>
                        @endif
                    </div>

                    <label for="address">{{ Lang::label('Address') }}</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('address') ? 'error focused' : '' }}">
                            <textarea name="address" type="text" id="address" class="form-control" placeholder="{{ Lang::label('Enter Address') }}">{{ (old('address')?old('address'):$client->address) }}</textarea>
                        </div>
                        @if ($errors->has('address'))
                            <label class="error">{{ $errors->first('address') }}</label>
                        @endif
                    </div>

                    <label for="status">{{ Lang::label('Status') }}</label>
                    <div class="form-group">
                        <div class="switch {{ $errors->has('status') ? 'error focused' : '' }}">
                            <label>
                                Deactive<input name="status" type="checkbox" {{ (($client->status==1)?'checked':null) }}>
                                <span class="lever"></span>Active
                            </label>
                            @if ($errors->has('status'))
                                <label class="error">{{ $errors->first('status') }}</label>
                            @endif
                        </div>
                    </div>

                    <div class="form-group text-right">
                        <button type="reset" class="btn btn-danger waves-effect">{{ Lang::label('Reset') }}</button>
                        <button type="submit" class="btn btn-success waves-effect">{{ Lang::label('Update') }}</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="card">
            <div class="header">
                <h2>{{ $title2 }}</h2>
                <ul class="header-dropdown m-r--5">
                    <li>
                        <a href="#" data-toggle="modal" data-target="#newVehicle" class="btn btn-sm btn-success waves-effect">
                            <i class="material-icons">add</i>
                            <span>{{ Lang::label('New Vehicle') }}</span>
                        </a>
                    </li>    
                </ul>
            </div>  


            <div class="body"> 
                <div class="row">
                    <div class="col-sm-12">
                        <table class="dataTable table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ Lang::label('Vehicle Photo') }}</th>
                                    <th>{{ Lang::label('Vehicle Licence') }}</th>
                                    <th>{{ Lang::label('Color') }}</th>
                                    <th>{{ Lang::label('Note') }}</th>
                                    <th>{{ Lang::label('Status') }}</th>
                                    <th>{{ Lang::label('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vehicles as $v)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td><a href="{{ url(!empty($v->photo)?$v->photo:'public/assets/images/icons/default.jpg') }}" target="_blank"><img src="{{ asset(!empty($v->photo)?$v->photo:'public/assets/images/icons/default.jpg') }}" alt="" width="50" /></a></td>
                                    <td>{{ $v->licence }}</td>
                                    <td>{{ $v->color }}</td>
                                    <td>{{ $v->note }}</td>
                                    <td><?= $v->status==1?('<span class="label label-success">'.Lang::label("Enabled").'</span>'):('<span class="label label-danger">'.Lang::label("Disabled").'</span>') ?></td>
                                    <td>
                                        @if ($v->status==1)
                                        <a  onClick="return confirm('Are you sure?')" href="{{ url("admin/client/vehicle/status/$v->status/$v->id") }}" title="{{ Lang::label("Disabled") }}" class="btn btn-xs btn-danger waves-effect"><i class="material-icons">close</i></a>
                                        @else 
                                        <a  onClick="return confirm('Are you sure?')" href="{{ url("admin/client/vehicle/status/$v->status/$v->id") }}" title="{{ Lang::label("Enabled") }}" class="btn btn-xs btn-success waves-effect"><i class="material-icons">check</i></a>
                                        @endif 
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 

<!-- New Vehicle Modal -->
<div class="modal fade" id="newVehicle" tabindex="-1" role="dialog" aria-labelledby="newVehicleLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="newVehicleLabel">{{ Lang::label("New Vehicle") }}</h4>
      </div>
      <div class="modal-body">
        <div id="newVehicleOutput"></div>

        {!! Form::open(['url' => '/admin/client/vehicle', 'files' => true, 'id'=>'newVehicleFrm']) !!}

        {{ Form::hidden('client_id_no', $client->id_no) }}

        <label for="licence">{{ Lang::label('Vehicle Licence') }} *</label>
        <div class="form-group">
            <div class="form-line  {{ $errors->has('licence') ? 'error focused' : '' }}">
                <input name="licence" type="text" id="licence" class="form-control" placeholder="{{ Lang::label('Enter Vehicle Licence No.') }}" value="{{ (old('licence')?old('licence'):$client->licence) }}">
            </div>
            @if ($errors->has('licence'))
                <label class="error">{{ $errors->first('licence') }}</label>
            @endif
        </div> 
 
        <label for="photo">{{ Lang::label('Vehicle Photo') }} </label>
        <div class="form-group">
            <div class="form-line  {{ $errors->has('photo') ? 'error focused' : '' }}">
                <input name="photo" type="file" id="photo" class="form-control">
            </div>
            @if ($errors->has('photo'))
                <label class="error">{{ $errors->first('photo') }}</label>
            @endif
        </div>

        <label for="color">{{ Lang::label('Color') }} </label>
        <div class="form-group">
            <div class="form-line  {{ $errors->has('color') ? 'error focused' : '' }}">
                <input name="color" type="text" id="color" class="form-control" placeholder="{{ Lang::label('Enter Color') }}" value="{{ (old('color')?old('color'):$client->color) }}">
            </div>
            @if ($errors->has('color'))
                <label class="error">{{ $errors->first('color') }}</label>
            @endif
        </div> 

        <label for="note">{{ Lang::label('Note') }} </label>
        <div class="form-group">
            <div class="form-line  {{ $errors->has('note') ? 'error focused' : '' }}">
                <textarea name="note" type="text" id="note" class="form-control" placeholder="{{ Lang::label('Note About Vehicle') }} ">{{ (old('note')?old('note'):$client->note) }}</textarea>
            </div>
            @if ($errors->has('note'))
                <label class="error">{{ $errors->first('note') }}</label>
            @endif
        </div>

        <div class="col-sm-12">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="switch {{ $errors->has('status') ? 'error focused' : '' }}">
                            <label>
                                Off<input name="status" type="checkbox" {{ (($client->status==1)?'checked':null) }}>
                                <span class="lever"></span>On
                            </label>
                            @if ($errors->has('status'))
                                <label class="error">{{ $errors->first('status') }}</label>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-8 text-right">
                        <button type="reset" class="btn btn-danger waves-effect">{{ Lang::label('Reset') }}</button>
                        <button type="submit" class="btn btn-success waves-effect">{{ Lang::label('Update') }} </button>
                    </div>
                </div>
            </div>
        </div>

        {!! Form::close() !!}
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
$(document).ready(function(){
    $("body").on("submit", "#newVehicleFrm", function(e){
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: $(this).attr("action"),
            type: $(this).attr("method"),
            dataType: 'json',
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data.status) {
                    $("#newVehicleOutput").html("<div class='alert alert-success'>"+data.message+"</div>");

                    setTimeout(function(){
                        location.reload();
                    }, 3000);
                } else {
                    $("#newVehicleOutput").html("<div class='alert alert-danger'>"+data.message+"</div>");
                }
            },
            error: function(xhr) {
                console.log('failed', xhr);
            }
        });
    });
})
</script>
@endsection
