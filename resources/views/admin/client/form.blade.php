@extends('admin.template')

@section('content')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card"> 
            <div class="header">
                <h2>{{ $title }}</h2>
                <ul class="header-dropdown m-r--5">
                    <li>
                        <a href="{{ url('admin/client/list') }}" title="Client List" class="btn btn-sm btn-primary waves-effect">
                            <i class="material-icons">list</i>
                            <span>{{ Lang::label('Clients') }}</span>
                        </a> 
                    </li>  
                </ul>
            </div> 


            <div class="body">
                {!! Form::open(['url' => '/admin/client/new', 'class' => 'form-validation frmValidation', 'files' => true]) !!}
                <div class="row">
                    <div class="col-sm-6">
                        <label for="name">{{ Lang::label('Name') }} *</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('name') ? 'error focused' : '' }}">
                                <input name="name" type="text" id="name" class="form-control" placeholder="{{ Lang::label('Enter Name') }}" value="{{ old('name') }}">
                            </div>
                            @if ($errors->has('name'))
                                <label class="error">{{ $errors->first('name') }}</label>
                            @endif
                        </div>

                        <label for="mobile">{{ Lang::label('Phone / Mobile') }} *</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('mobile') ? 'error focused' : '' }}">
                                <input name="mobile" type="text" id="mobile" class="form-control" placeholder="{{ Lang::label('Enter Phone or Mobile No.') }}" value="{{ old('mobile') }}">
                            </div>
                            @if ($errors->has('mobile'))
                                <label class="error">{{ $errors->first('mobile') }}</label>
                            @endif
                        </div>

                        <label for="email">{{ Lang::label('Email') }} *</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('email') ? 'error focused' : '' }}">
                                <input name="email" type="text" id="email" class="form-control" placeholder="{{ Lang::label('Enter Email Address') }}" value="{{ old('email') }}">
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
                                <textarea name="address" type="text" id="address" class="form-control" placeholder="{{ Lang::label('Enter Address') }}" >{{ old('address') }}</textarea>
                            </div>
                            @if ($errors->has('address'))
                                <label class="error">{{ $errors->first('address') }}</label>
                            @endif
                        </div>
                    </div>


                    <div class="col-sm-6">
                        <label for="vehicle_licence">{{ Lang::label('Vehicle Licence') }} *</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('vehicle_licence') ? 'error focused' : '' }}">
                                <input name="vehicle_licence" type="text" id="vehicle_licence" class="form-control" placeholder="{{ Lang::label('Enter Vehicle Licence No.') }}" value="{{ old('vehicle_licence') }}">
                            </div>
                            @if ($errors->has('vehicle_licence'))
                                <label class="error">{{ $errors->first('vehicle_licence') }}</label>
                            @endif
                        </div> 

                        <label for="vehicle_photo">{{ Lang::label('Vehicle Photo') }}</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('vehicle_photo') ? 'error focused' : '' }}">
                                <input name="vehicle_photo" type="file" id="vehicle_photo" class="form-control">
                                <input name="old_vehicle_photo" type="hidden" value="{{ old('vehicle_photo') }}">
                            </div>
                            @if ($errors->has('vehicle_photo'))
                                <label class="error">{{ $errors->first('vehicle_photo') }}</label>
                            @endif
                        </div>

                        <label for="color">{{ Lang::label('Color') }}</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('color') ? 'error focused' : '' }}">
                                <input name="color" type="text" id="color" class="form-control" placeholder="{{ Lang::label('Enter Color') }}" value="{{ old('color') }}">
                            </div>
                            @if ($errors->has('color'))
                                <label class="error">{{ $errors->first('color') }}</label>
                            @endif
                        </div> 

                        <label for="note">{{ Lang::label('Note') }} </label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('note') ? 'error focused' : '' }}">
                                <textarea name="note" type="text" id="note" class="form-control" placeholder="{{ Lang::label('Note About Vehicle') }}">{{ old('note') }}</textarea>
                            </div>
                            @if ($errors->has('note'))
                                <label class="error">{{ $errors->first('note') }}</label>
                            @endif
                        </div>


                    <div class="col-sm-12">
                        <label for="status">{{ Lang::label('Status') }}</label>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="switch {{ $errors->has('status') ? 'error focused' : '' }}">
                                        <label>
                                            OFF<input name="status" type="checkbox" checked="" value="1">
                                            <span class="lever"></span>ON
                                        </label>
                                        @if ($errors->has('status'))
                                            <label class="error">{{ $errors->first('status') }}</label>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <button type="reset" class="btn btn-danger waves-effect">{{ Lang::label('Reset') }}</button>
                                    <button type="submit" class="btn btn-success waves-effect">{{ Lang::label('Save') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    </div> 
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
