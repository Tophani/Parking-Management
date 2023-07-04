@extends('admin.template')
 
@section('content')
<!-- Exportable Table -->
<div class="row clearfix">
    <div class="col-sm-12">
        <div class="card">
            <div class="header">
                <h2>
                    {{ $title }}
                    <a href="{{ url('admin/user/new') }}" title="New Admin " class="pull-right btn btn-xs btn-primary waves-effect"><i class="material-icons">add</i></a>
                </h2>
            </div> 

            <div class="body"> 
                <div class="xtable-responsive">
                    <table class="table table-condensed table-striped dataTable-admin">
                        <thead>
                            <tr>
                                <th>{{ Lang::label('SL No.') }}</th>
                                <th>{{ Lang::label('Photo') }}</th>
                                <th>{{ Lang::label('Name') }}</th>
                                <th>{{ Lang::label('Email') }}</th>
                                <th>{{ Lang::label('Created at') }}</th>
                                <th>{{ Lang::label('Updated at') }}</th>
                                <th>{{ Lang::label('Status') }}</th>
                                <th>{{ Lang::label('User Role') }}</th>
                                <th>{{ Lang::label('Parking Zone') }}</th>
                                <th>{{ Lang::label('Action') }}</th> 
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>{{ Lang::label('SL No.') }}</th>
                                <th>{{ Lang::label('Photo') }}</th>
                                <th>{{ Lang::label('Name') }}</th>
                                <th>{{ Lang::label('Email') }}</th>
                                <th>{{ Lang::label('Created at') }}</th>
                                <th>{{ Lang::label('Updated at') }}</th>
                                <th>{{ Lang::label('Status') }}</th>
                                <th>{{ Lang::label('User Role') }}</th>
                                <th>{{ Lang::label('Parking Zone') }}</th>
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
    $('.dataTable-admin').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        pageLength: 25, // default records per page
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        processing: true,
        serverSide: true,
        ajax: "{{ url('admin/user/data') }}",
        columns: [ 
            {data: 'rownum', name: 'rownum', searchable: false},
            {data: 'profile',   name: 'profile'},
            {data: 'name',   name: 'name'},
            {data: 'email',  name: 'email'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {data: 'status', name: 'status'},
            {data: 'user_role', name: 'user_role'},
            {data: 'zone', name: 'zone'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
    });
});
</script>
@endsection
