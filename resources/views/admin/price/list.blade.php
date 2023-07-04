@extends('admin.template')
 
@section('content')
<!-- Exportable Table -->
<div class="row clearfix">
    <!-- price list -->
    <div class="col-sm-12">
        <div class="card">

            <div class="header">
                <h2>{{ $title }}</h2>
                <ul class="header-dropdown m-r--5">  
                    <li>
                        <a href="{{ url('admin/price/new') }}" class="btn btn-sm btn-success waves-effect">
                            <i class="material-icons">add</i>
                            <span>{{ Lang::label('New Price') }}</span>
                        </a>
                    </li>   
                </ul>
            </div>  

            <div class="body"> 
                <div class="xtable-responsive">
                    <table class="table table-condensed table-striped dataTable-price">
                        <thead>
                            <tr>
                                <th>{{ Lang::label('SL No.') }}</th>
                                <th>{{ Lang::label('Parking Zone') }}</th>
                                <th>{{ Lang::label('Status') }}</th>
                                <th>{{ Lang::label('Action') }}</th> 
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>{{ Lang::label('SL No.') }}</th>
                                <th>{{ Lang::label('Parking Zone') }}</th>
                                <th>{{ Lang::label('Status') }}</th>
                                <th>{{ Lang::label('Action') }}</th> 
                            </tr>
                        </tfoot>  
                    </table>
                </div>
            </div>
        </div>
    </div>  
</div>
<!-- #END# Exportable Table -->
@endsection

@section('scripts')
<script type="text/javascript">
$(document).ready(function() {  
    $('.dataTable-price').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        pageLength: 25, // default records per page
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        processing: true,
        serverSide: true,
        ajax: "{{ url('admin/price/data') }}",
        columns: [ 
            {data: 'rownum', name: 'rownum', searchable: false},
            {data: 'place_name',   name: 'place_name'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
    });
});
</script>
@endsection
