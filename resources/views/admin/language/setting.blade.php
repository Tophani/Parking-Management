@extends('admin.template')
 
@section('content')
<!-- Exportable Table -->
<div class="row clearfix">
    <div class="col-sm-12">
        <div class="card">
            <div class="header">
                <h2>
                    {{ $title }}
                </h2>
            </div> 

            <div class="body">  
                <div class="row">
                    <div class="col-sm-7">
                    	{!! Form::open(['url' => 'admin/language/add']) !!}
                        <label for="name">{{ Lang::label("Add New Language") }} *</label>
                        <div class="input-group">
                            <div class="form-line  {{ $errors->has('name') ? 'error focused' : '' }}">
                                <input name="name" type="text" id="name" class="form-control" placeholder="{{ Lang::label("Enter Language Name") }}" value="{{ old('name') }}">
                            </div>
                            @if ($errors->has('name'))
                                <label class="error">{{ $errors->first('name') }}</label>
                            @endif

                            <div class="input-group-btn">
                            	<button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </div>
						{!! Form::close() !!}
                    </div>


                    <div class="col-sm-7">
	                    <table class="table table-condensed table-striped dataTable-client"> 
	                        <thead>
	                            <tr>
	                                <th>#</th>
	                                <th>{{ Lang::label("Language List") }}</th>
	                                <th>{{ Lang::label("Action") }}</th>
	                            </tr>
	                        </thead> 
	                        <tbody>
								@if (!empty($languages))
								@foreach ($languages as $language)
								<tr>
								    <th>{{ $loop->index+1 }}</th>
								    <th><span class="text-capitalize">{{ $language }}</span></th> 
								    <th>
								    	@if ($language!=$setting) 
								    		<a href="{{ url("admin/language/default/$language") }}" class="btn btn-success" onclick="return confirm('Are you sure ? ')">{{ Lang::label('Active') }}</a>
								    	@else
								    		<a href="#" class="btn btn-warning" disabled>{{ Lang::label('Activated') }}</a>
								    	@endif


								    	@if ($language=="default")
								    		<a href="#" class="btn btn-sm btn-danger" disabled>{{ Lang::label('Delete') }}</a>
								    	@else
								    		<a href="{{ url("admin/language/delete/$language") }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure ? ')">{{ Lang::label('Delete') }}</a>
								    	@endif
								    </th>
								</tr>
								@endforeach
								@endif
	                        </tbody> 
	                    </table>
	                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- #END# Exportable Table -->

@endsection
