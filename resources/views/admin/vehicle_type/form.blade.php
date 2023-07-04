@extends('admin.template')

@section('content')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">

            <div class="header">
                <h2>{{ $title }}</h2>
                <ul class="header-dropdown m-r--5">   
                    <li>
                        <a href="{{ url('admin/vehicle_type/list') }}" class="btn btn-sm btn-primary waves-effect">
                            <i class="material-icons">list</i>
                            <span>{{ Lang::label('Vehicle Types') }}</span>
                        </a> 
                    </li>  
                </ul>
            </div> 


            <div class="body">
                {!! Form::open(['url' => 'admin/vehicle_type/new', 'class' => 'form-validation frmValidation', 'files' => true]) !!}

                    <label for="name">{{ Lang::label('Vehicle Type') }} *</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('name') ? 'error focused' : '' }}">
                            <input name="name" type="text" id="name" class="form-control" placeholder="{{ Lang::label('Vehicle Type') }}" value="{{ old('name') }}">
                        </div>
                        @if ($errors->has('name'))
                            <label class="error">{{ $errors->first('name') }}</label>
                        @endif
                    </div>

                    <label for="email">{{ Lang::label('Description') }} </label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('description') ? 'error focused' : '' }}">
                            <textarea name="description" type="text" id="description" class="form-control" placeholder="{{ Lang::label('Description') }}">{{ old('description') }}</textarea>
                        </div>
                        @if ($errors->has('description'))
                            <label class="error">{{ $errors->first('description') }}</label>
                        @endif
                    </div> 

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
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
