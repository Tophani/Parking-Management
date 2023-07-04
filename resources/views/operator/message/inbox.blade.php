@extends('operator.template')
 
@section('content')
<!-- Exportable Table -->
<div class="row clearfix">
    <div class="col-sm-12">
        <div class="card">
            <div class="header">
                <h2>
                    {{ $title }}
                    <a href="{{ url('operator/message/new') }}" title="New Message" class="pull-right btn btn-xs btn-primary waves-effect"><i class="material-icons">add</i></a>
                </h2>
            </div> 

            <div class="body"> 
                <div class="xtable-responsive">
                    <table class="table table-condensed table-striped dataTable-message-inbox">
                        <thead>
                            <tr>
                                <th>{{ Lang::label('SL No.') }}</th>
                                <th>{{ Lang::label('Photo') }}</th>
                                <th>{{ Lang::label('Sender') }}</th>
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
                                <th>{{ Lang::label('Sender') }}</th>
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
    $('.dataTable-message-inbox').DataTable({
        order: [], //reset auto order
        processing: true,
        responsive: true,
        serverSide: true,
        pageLength: 25, // default records per page
        select: true,
        "bInfo": true,
        pagingType: "full_numbers",
        dom: "<'row'<'col-sm-12'i><'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>tp", 
        buttons: [  
            {
                extend: 'copy', 
                exportOptions: {
                    columns: ':visible'
                }
            }, 
            {
                extend: 'csv', 
                exportOptions: {
                    columns: ':visible'
                }
            }, 
            {
                extend: 'excel', 
                exportOptions: {
                    columns: ':visible'
                }
            }, 
            {
                extend: 'pdf', 
                exportOptions: {
                    columns: ':visible'
                }
            }, 
            {
                extend: 'print', 
                exportOptions: {
                    columns: ':visible'
                } 
            },   
            {extend: 'colvis'} 
        ], 
        ajax: "{{ url('operator/message/inbox/data') }}",
        columns: [ 
            {data: 'rownum', name: 'rownum', searchable: false},
            {data: 'photo',   name: 'photo'},
            {data: 'sender',   name: 'sender'},
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