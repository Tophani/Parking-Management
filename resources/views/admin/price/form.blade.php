@extends('admin.template')

@section('content')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">

            <div class="header">
                <h2>{{ $title }}</h2>
                <ul class="header-dropdown m-r--5">   
                    <li>
                        <a href="{{ url('admin/price/list') }}" title="Prices" class="btn btn-sm btn-primary waves-effect">
                            <i class="material-icons">list</i>
                            <span>{{ Lang::label('Prices') }}</span>
                        </a> 
                    </li>  
                </ul>
            </div>  


            <div class="body">
                <div class="row">
                    <div class="col-sm-12"> 
                    {!! Form::open(['url' => 'admin/price/new', 'class' => 'form-validation']) !!}

                        <label for="place_id">{{ Lang::label('Parking Zone') }} *</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('place_id') ? 'error focused' : '' }}">
                                {{ Form::select('place_id', $placeList,  old('place_id'), ['class'=>'form-control', 'id'=>'place_id', 'placeholder'=>Lang::label('Select Option')]) }} 
                            </div>
                            @if ($errors->has('place_id'))
                                <label class="error">{{ $errors->first('place_id') }}</label>
                            @endif
                        </div> 

                        <label for="price">{{ Lang::label('Time & Price') }} * </label>
                        <div class="form-group" id="price">
                            <div class="row priceContent">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <div class="form-line  {{ $errors->has('vehicle_type_id') ? 'error focused' : '' }}">
                                            {{ Form::select('vehicle_type_id[]', $vehicleTypeList,  null, ['class'=>'form-control no-select', 'id'=>'vehicle_type_id', 'placeholder'=>Lang::label('Select Option'), 'required'=>'required']) }} 
                                        </div>
                                        @if ($errors->has('vehicle_type_id'))
                                            <label class="error">{{ $errors->first('vehicle_type_id') }}</label>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-line  {{ $errors->has('time') ? 'error focused' : '' }}">
                                        <input name="time[]" type="number" id="time" class="form-control" placeholder="{{ Lang::label('Time') }}" min="1" value="" required>
                                    </div>
                                    @if ($errors->has('time'))
                                        <label class="error">{{ $errors->first('time') }}</label>
                                    @endif
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-line  {{ $errors->has('unit') ? 'error focused' : '' }}">
                                        {{ Form::select('unit[]', $unitList,  null, ['class'=>'form-control no-select', 'id'=>'unit', 'placeholder'=>Lang::label('Select Option'), 'required'=>'required']) }} 
                                    </div>
                                    @if ($errors->has('unit'))
                                        <label class="error">{{ $errors->first('unit') }}</label>
                                    @endif
                                </div> 
                                <div class="col-sm-2">
                                    <div class="form-line  {{ $errors->has('price') ? 'error focused' : '' }}">
                                        <input name="price[]" type="text" id="price" class="form-control" placeholder="{{ Lang::label('Price') }}" value="" required>
                                    </div>
                                    @if ($errors->has('price'))
                                        <label class="error">{{ $errors->first('price') }}</label>
                                    @endif
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-success waves-effect btn-xs addItem"><i class="material-icons">add</i></button>
                                    <button type="button" class="btn btn-danger waves-effect btn-xs removeItem"><i class="material-icons">remove</i></button>
                                </div>
                            </div>
                        </div>

                        <label for="note">{{ Lang::label('Note') }}</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('note') ? 'error focused' : '' }}">
                                <textarea name="note" type="text" id="note" class="form-control" placeholder="{{ Lang::label('Note') }}">{{ old('note') }}</textarea>
                            </div>
                            @if ($errors->has('note'))
                                <label class="error">{{ $errors->first('note') }}</label>
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
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
    $("body").on('click', '.addItem', function(){
        var data = $(".priceContent").html();
        $(this).parent().parent().parent().append("<div class='row'>"+data+'</div>');
    });
    $("body").on('click', '.removeItem', function(){
        $(this).parent().parent().empty();
    });

});
</script>
@endsection
