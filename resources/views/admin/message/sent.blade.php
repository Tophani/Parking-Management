@extends('admin.template')
 
@section('content')
<!-- Exportable Table -->
<div class="row clearfix">
    <div class="col-sm-12">
        <div class="card">
            <div class="header">
                <h2>
                    {{ $title }}
                    <a href="{{ url('admin/message/new') }}" title="New Message" class="pull-right btn btn-xs btn-primary waves-effect"><i class="material-icons">add</i></a>
                </h2>
            </div> 

            <div class="body"> 
                <div class="xtable-responsive">
                    <table class="table table-condensed table-striped dataTable-message-sent">
                        <thead>
                            <tr>
                                <th>{{ Lang::label('SL No.') }}</th>
                                <th>{{ Lang::label('Photo') }}</th>
                                <th>{{ Lang::label('Send To') }}</th>
                                <th>{{ Lang::label('Subject') }}</th>
                                <th>{{ Lang::label('Message') }}</th>
                                <th>{{ Lang::label('Date') }}</th>
                                <th>{{ Lang::label('Status') }}</th>
                                <th>{{ Lang::label('Action') }}</th> 
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>{{ Lang::label('SL No.') }}</th>
                                <th>{{ Lang::label('Photo') }}</th>
                                <th>{{ Lang::label('Send To') }}</th>
                                <th>{{ Lang::label('Subject') }}</th>
                                <th>{{ Lang::label('Message') }}</th>
                                <th>{{ Lang::label('Date') }}</th>
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
    $('.dataTable-message-sent').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        pageLength: 25, // default records per page
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        processing: true,
        serverSide: true,
        ajax: "{{ url('admin/message/sent/data') }}",
        columns: [ 
            {data: 'rownum', name: 'rownum', searchable: false},
            {data: 'photo',   name: 'photo'},
            {data: 'send_to',   name: 'send_to'},
            {data: 'subject', name: 'subject'},
            {data: 'message', name: 'message'},
            {data: 'date', name: 'date'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
    });
});
</script>
@endsection 