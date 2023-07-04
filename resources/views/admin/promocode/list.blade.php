@extends('admin.template')
 
@section('content')
<!-- Exportable Table -->
<div class="row clearfix">
    <div class="col-sm-12">
        <div class="card">

            <div class="header">
                <h2>{{ $title }}</h2>
                <ul class="header-dropdown m-r--5">   
                    <li>
                        <a href="{{ url('admin/promocode/new') }}" class="btn btn-sm btn-success waves-effect">
                            <i class="material-icons">add</i>
                            <span>{{ Lang::label('New Promo Code') }}</span>
                        </a>
                    </li>   
                </ul>
            </div> 

            <div class="body"> 
                <div class="xtable-responsive">
                    <table class="table table-condensed table-striped dataTable-promocode">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Description</th> 
                                <th>Promo Code</th> 
                                <th>Discount</th> 
                                <th>Limit</th> 
                                <th>Used</th> 
                                <th>Start Date</th> 
                                <th>End Date</th> 
                                <th>Status</th> 
                                <th>Action</th>  
                            </tr>
                        </thead> 
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Description</th> 
                                <th>Promo Code</th> 
                                <th>Discount</th> 
                                <th>Limit</th> 
                                <th>Used</th> 
                                <th>Start Date</th> 
                                <th>End Date</th> 
                                <th>Status</th> 
                                <th>Action</th>  
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
    $('.dataTable-promocode').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        pageLength: 25, // default records per page
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        processing: true,
        serverSide: true,
        ajax: "{{ url('admin/promocode/data') }}",
        columns: [ 
            {data: 'rownum', name: 'rownum', searchable: false},
            {data: 'name',   name: 'name'},
            {data: 'description',  name: 'description'},
            {data: 'promocode', name: 'promocode'},
            {data: 'discount', name: 'discount'},
            {data: 'limit', name: 'limit'},
            {data: 'used', name: 'used'},
            {data: 'start_date', name: 'start_date'},
            {data: 'end_date', name: 'end_date'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ], 
    });
});
</script>
@endsection
