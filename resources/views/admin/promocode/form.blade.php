@extends('admin.template')

@section('content')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">

            <div class="header">
                <h2>{{ $title }}</h2>
                <ul class="header-dropdown m-r--5">   
                    <li>
                        <a href="{{ url('admin/promocode/list') }}" class="btn btn-sm btn-primary waves-effect">
                            <i class="material-icons">list</i>
                            <span>{{ Lang::label('Promo Codes') }}</span>
                        </a> 
                    </li>  
                </ul>
            </div> 

            <div class="body">
                {!! Form::open(['url' => 'admin/promocode/new', 'class' => 'form-validation', 'files' => true]) !!}

                <div class="row">
                    <div class="col-sm-6">
                        <label for="name">{{ Lang::label('Offer Name') }} *</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('name') ? 'error focused' : '' }}">
                                <input name="name" type="text" id="name" class="form-control" placeholder="{{ Lang::label('Offer Name') }}" value="{{ old('name') }}">
                            </div>
                            @if ($errors->has('name'))
                                <label class="error">{{ $errors->first('name') }}</label>
                            @endif
                        </div>


                        <label for="description">{{ Lang::label('Description') }} </label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('description') ? 'error focused' : '' }}">
                                <input name="description" type="text" id="description" class="form-control" placeholder="{{ Lang::label('Description') }} " value="{{ old('description') }}">
                            </div>
                            @if ($errors->has('description'))
                                <label class="error">{{ $errors->first('description') }}</label>
                            @endif
                        </div>
     

                        <label for="promocode">{{ Lang::label('Promo Code') }}  *</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('promocode') ? 'error focused' : '' }}">
                                <input name="promocode" type="text" id="promocode" class="form-control" placeholder="{{ Lang::label('Promo Code') }} " value="{{ old('promocode') }}">
                            </div>
                            @if ($errors->has('promocode'))
                                <label class="error">{{ $errors->first('promocode') }}</label>
                            @endif
                        </div>

                        <label for="discount">{{ Lang::label('Discount') }}  *</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('discount') ? 'error focused' : '' }}">
                                <input name="discount" type="text" id="discount" class="form-control" placeholder="{{ Lang::label('Discount') }} " value="{{ old('discount') }}">
                            </div>
                            @if ($errors->has('discount'))
                                <label class="error">{{ $errors->first('discount') }}</label>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label for="limit">{{ Lang::label('Limit') }}  *</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('limit') ? 'error focused' : '' }}">
                                <input name="limit" type="text" id="limit" class="form-control" placeholder="{{ Lang::label('Limit') }} " value="{{ old('limit') }}">
                            </div>
                            @if ($errors->has('limit'))
                                <label class="error">{{ $errors->first('limit') }}</label>
                            @endif
                        </div>

                        <label for="start_date">{{ Lang::label('Start Date') }}  *</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('start_date') ? 'error focused' : '' }}">
                                <input name="start_date" type="text" id="start_date" class="form-control datepicker" placeholder="{{ Lang::label('Start Date') }} " value="{{ old('start_date') }}" autocomplete="off">
                            </div>
                            @if ($errors->has('start_date'))
                                <label class="error">{{ $errors->first('start_date') }}</label>
                            @endif
                        </div> 

                        <label for="end_date">{{ Lang::label('End Date') }}  *</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('end_date') ? 'error focused' : '' }}">
                                <input name="end_date" type="text" id="end_date" class="form-control datepicker" placeholder="{{ Lang::label('End Date') }} " value="{{ old('end_date') }}" autocomplete="off">
                            </div>
                            @if ($errors->has('end_date'))
                                <label class="error">{{ $errors->first('end_date') }}</label>
                            @endif
                        </div> 


                        <label for="status">{{ Lang::label('Status') }} </label>
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
                                    <button type="reset" class="btn btn-danger waves-effect">{{ Lang::label('Reset') }} </button>
                                    <button type="submit" class="btn btn-success waves-effect">{{ Lang::label('Save') }} </button>
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
