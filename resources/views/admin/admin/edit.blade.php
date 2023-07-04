@extends('admin.template')

@section('content')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    {{ $title }}
                    <a href="{{ url('admin/user/list') }}" title="Admin List" class="pull-right btn btn-xs btn-primary waves-effect"><i class="material-icons">list</i></a>
                </h2>
            </div>


            <div class="body">
                {!! Form::open(['url' => 'admin/user/edit/', 'class' => 'form-validation frmValidation', 'files' => true]) !!}

                    {!! Form::hidden('id', $user->id) !!}

                    <label for="name">{{ Lang::label('Name') }} *</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('name') ? 'error focused' : '' }}">
                            <input name="name" type="text" id="name" class="form-control" placeholder="{{ Lang::label('Name') }}" value="{{ $user->name }}">
                        </div>
                        @if ($errors->has('name'))
                            <label class="error">{{ $errors->first('name') }}</label>
                        @endif
                    </div>

                    <label for="email">{{ Lang::label('Email') }} *</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('email') ? 'error focused' : '' }}">
                            <input name="email" type="text" id="email" class="form-control" placeholder="{{ Lang::label('Email') }}" value="{{ $user->email }}">
                        </div>
                        @if ($errors->has('email'))
                            <label class="error">{{ $errors->first('email') }}</label>
                        @endif
                    </div>

                    <label for="password">{{ Lang::label('Password') }} *</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('password') ? 'error focused' : '' }}">
                            <input name="password" type="password" id="password" class="form-control" placeholder="{{ Lang::label('Password') }}">
                        </div>
                        @if ($errors->has('password'))
                            <label class="error">{{ $errors->first('password') }}</label>
                        @endif
                    </div>


                    <label for="conf_password">{{ Lang::label('Confirm Password') }} *</label>
                    <div class="form-group">
                        <div class="form-line {{ $errors->has('conf_password') ? 'error focused' : '' }}">
                            <input name="conf_password" type="password" id="conf_password" class="form-control" placeholder="{{ Lang::label('Confirm Password') }}">
                        </div>
                        @if ($errors->has('conf_password'))
                            <label class="error">{{ $errors->first('conf_password') }}</label>
                        @endif
                    </div>


                    <div class="input-group">
                        <label for="user_role">{{ Lang::label("User Role") }} *</label>
                        <div class="form-line  {{ $errors->has('user_role') ? 'error focused' : '' }}">
                            {{ Form::select('user_role', [''=>Lang::label('Select Option'), 'admin'=>Lang::label('Admin'), 'operator'=>Lang::label('Operator')], $user->user_role, ['class'=>'form-control', 'id'=>'user_role']) }} 
                        </div>
                        @if ($errors->has('user_role'))
                            <label class="error">{{ $errors->first('user_role') }}</label>
                        @endif 
                    </div>

                    <div class="input-group hide" id="place_id">
                        <label for="place_id">{{ Lang::label("Select Parking Zone") }} *</label>
                        <div class="form-line  {{ $errors->has('place_id') ? 'error focused' : '' }}">
                            <ul class="list-unstyled"> 
                            @foreach($placeList as $placeId => $placeName)   
                            <li>           
                                {!! Form::checkbox('place_id[]', $placeId, (in_array($placeId, explode(",", $user->place_id))?true:false), array('id'=> 'chkbox'.$loop->index, 'class'=>'filled-in chk-col-cyan')) !!}
                                {!! Form::label('chkbox'.$loop->index, $placeName) !!}
                            </li>
                            @endforeach
                            </ul>
                        </div>
                        @if ($errors->has('place_id'))
                            <label class="error">{{ $errors->first('place_id') }}</label>
                        @endif 
                    </div>



                    <label for="photo">{{ Lang::label('Photo') }}</label>
                    <div class="form-group"> 
                        <div class="form-line  {{ $errors->has('photo') ? 'error focused' : '' }}">
                        <div class="row">
                            <div class="col-sm-3"> 
                                <img src='{{ asset($user->photo?$user->photo:"public/assets/images/icons/user.png") }}' width="150" height="100" class="img-thumbnail">
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

                    <label for="status">{{ Lang::label('Status') }}</label>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="switch {{ $errors->has('status') ? 'error focused' : '' }}">
                                    <label>
                                        OFF<input name="status" type="checkbox" {{ ($user->status?'checked':null) }}>
                                        <span class="lever"></span>ON
                                    </label>
                                    @if ($errors->has('status'))
                                        <label class="error">{{ $errors->first('status') }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 text-right">
                                <button type="reset" class="btn btn-danger waves-effect">{{ Lang::label('Reset') }}</button>
                                <button type="submit" class="btn btn-success waves-effect">{{ Lang::label('Update') }}</button>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

<!-- JavaScript -->
@section('scripts')
<script type="text/javascript">
$(document).ready(function()
{   
    $("#user_role").change(function(){
        showHideParkingZones($(this).val());
    });

    $(window).on("load", function(){
        showHideParkingZones('{{ $user->user_role }}');
    });

    function showHideParkingZones(role)
    {
        if (role == "operator")
        {
            $('#place_id').slideDown(500).removeClass('hide');
        }
        else
        {
            $("#place_id").slideUp(500).addClass('hide');
        }
    }




});
</script>
@endsection
