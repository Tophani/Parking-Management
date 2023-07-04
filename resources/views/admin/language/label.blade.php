@extends('admin.template')
 
@section('content')
<!-- Exportable Table -->
<div class="row clearfix">
    <div class="col-sm-12">
        <div class="card">
            <div class="header">
                <h2>{{ Lang::label("Add New Label") }}</h2>
            </div> 

            <div class="body">  
            	<!-- Add Language -->
            	<div class="table-responsive"> 
                {!! Form::open(['url' => 'admin/language/label/add']) !!}
	                <table class="table table-condensed table-striped">
	                    <thead>
	                        <tr>
	                        	@if (!empty($columns))
	                        	@foreach ($columns as $column)
	                            	<th>{{ Lang::label($column) }}</th>
	                            @endforeach
	                            <th><i class="material-icons">setting</i></th>
	                            @endif
	                        </tr>
	                    </thead>  
	                    <tbody>
	                    	@if (!empty($languages))
	                        <tr>
	                        	@foreach ($columns as $column)
	                            	<td>
	                            		<textarea name="language[{{ $column }}]" class="form-control" placeholder="{{ Lang::label('Label Here!') }}"></textarea>
	                            	</td>
	                            @endforeach 
	                            <td>
	                				<button type="submit" class="btn btn-lg btn-success">{{ Lang::label('Save') }}</button>
	                            </td>
	                        </tr>    
	                        @endif
	                    </tbody>  
	                </table>
                {!! Form::close() !!}
            	</div> 
            </div>
        </div>
    </div>


    <div class="col-sm-12">
        <div class="card"> 
            <div class="header">
                <h2>
                    {{ Lang::label("Update Label") }}
                </h2>
            </div> 

            <div class="body">   
            	<!-- Update Language --> 

            	<div class="table-responsive"> 
                {!! Form::open(['url' => 'admin/language/label/update']) !!}
					
					 
                	<table width="100%">
                        <tr>
                            <td>
                            	<div class="btn-group">
				                	<button type="reset" class="btn btn-sm btn-warning">{{ Lang::label('Reset') }}</button>
				                	<button type="submit" class="btn btn-sm btn-success">{{ Lang::label('Update') }}</button>
			            		</div>
			            	</td>
                            <td><div class="text-right">{{ ($languages->links()?$languages->links():null) }}</div></td>
                        </tr>
                	</table>

	                <table class="table table-condensed table-striped dataTable">
	                    <thead>
	                        <tr>
	                        	@if (!empty($columns))
	                            <th>#</th>
	                        	@foreach ($columns as $column)
	                            	<th>{{ Lang::label($column) }}</th>
	                            @endforeach
	                            <th><i class="material-icons">setting</i></th>
	                            @endif
	                        </tr>
	                    </thead>  
	                    <tbody>
	                    	@if (!empty($languages))
	                    	@foreach ($languages as $language)
	                        <tr>
	                            <td>{{ $loop->index+1 }}</td>
	                        	@foreach ($columns as $column)
	                        		@if ($column == 'default')
	                        		<td>
	                        			<textarea cols="12" disabled="" class="form-control" >{{ $language->$column }}</textarea>
	                        		</td>
	                        		@else
	                            	<td class="{{ (empty($language->$column)?'has-error':null) }}">
	                            		<input type="hidden" name="id[]" value="{{ $language->id }}">
	                            		<input type="hidden" name="language[]" value="{{ $column }}">
	                            		<textarea cols="12" name="data[]" class="form-control" placeholder="{{ Lang::label('Label Here!') }}">{{ $language->$column }}</textarea>
	                            	</td>
	                            	@endif
	                            @endforeach 
	                            <td>
									<a href="{{ url("admin/language/label/delete/$language->id") }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure ? ')">{{ Lang::label('Delete') }}</a>
	                            </td>
	                        </tr>
	                        @endforeach
	                        @endif
	                    </tbody>  
	                </table>


                	<table width="100%">
                        <tr>
                            <td>
                            	<div class="btn-group">
				                	<button type="reset" class="btn btn-sm btn-warning">{{ Lang::label('Reset') }}</button>
				                	<button type="submit" class="btn btn-sm btn-success">{{ Lang::label('Update') }}</button>
			            		</div>
			            	</td>
                            <td><div class="text-right">{{ ($languages->links()?$languages->links():null) }}</div></td>
                        </tr>
                	</table>

                {!! Form::close() !!}
            	</div>
            </div>
        </div>
    </div>
</div>
<!-- #END# Exportable Table -->
<script type="text/javascript">
$(document).ready(function() {
    //datatable
    $('.dataTable').DataTable({ 
        responsive: false, 
        paging:false,
        dom: "<'row'<'col-sm-4'B><'col-sm-4'l><'col-sm-4'f>>tp",  
        buttons: [  
            {extend: 'copy', className: 'btn-sm'}, 
            {extend: 'csv', title: 'ExampleFile', className: 'btn-sm'}, 
            {extend: 'excel', title: 'ExampleFile', className: 'btn-sm', title: 'exportTitle'}, 
            {extend: 'pdf', title: 'ExampleFile', className: 'btn-sm'}, 
            {extend: 'print', className: 'btn-sm'} 
        ], 
    });
});
</script>
@endsection
 