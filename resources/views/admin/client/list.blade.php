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
                        <a href="{{ url('admin/client/new') }}" class="btn btn-sm btn-success waves-effect">
                            <i class="material-icons">add</i>
                            <span>{{ Lang::label('New Client') }}</span>
                        </a>
                    </li>  
                </ul>
            </div> 

            <div class="body"> 
                <div class="xtable-responsive">
                    <table class="table table-condensed table-striped dataTable-client">
                        <thead>
                            <tr>
                                <th>{{ Lang::label('SL No.') }}</th>
                                <th>{{ Lang::label('ID No.') }}</th>
                                <th>{{ Lang::label('Name') }}</th>
                                <th>{{ Lang::label('Phone / Mobile') }}</th>
                                <th>{{ Lang::label('Email') }}</th>
                                <th>{{ Lang::label('Address') }}</th>
                                <th>{{ Lang::label('Status') }}</th>
                                <th>{{ Lang::label('Action') }}</th>
                            </tr>
                        </thead> 
                        <tfoot>
                            <tr>
                                <th>{{ Lang::label('SL No.') }}</th>
                                <th>{{ Lang::label('ID No.') }}</th>
                                <th>{{ Lang::label('Name') }}</th>
                                <th>{{ Lang::label('Phone / Mobile') }}</th>
                                <th>{{ Lang::label('Email') }}</th>
                                <th>{{ Lang::label('Address') }}</th>
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
    $('.dataTable-client').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        pageLength: 25, // default records per page
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        processing: true,
        serverSide: true,
        ajax: "{{ url('admin/client/data') }}",
        columns: [ 
            {data: 'rownum', name: 'rownum', searchable: false},
            {data: 'id_no',   name: 'id_no'},
            {data: 'name',   name: 'name'},
            {data: 'mobile',  name: 'mobile'},
            {data: 'email',  name: 'email'},
            {data: 'address',  name: 'address'},
            {data: 'status', name: 'status'}, 
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
    });
});
</script>
@endsection
