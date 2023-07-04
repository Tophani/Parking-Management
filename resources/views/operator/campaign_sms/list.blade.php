@extends('operator.template')
 
@section('content')
<!-- Exportable Table -->
<div class="row clearfix">
    <div class="col-sm-12">
        <div class="card">
            <div class="header">
                <h2>
                    {{ $title }}
                    <a href="{{ url('operator/client/new') }}" title="New Client " class="pull-right btn btn-xs btn-primary waves-effect"><i class="material-icons">add</i></a>
                </h2>
            </div> 

            <div class="body"> 
                <div class="xtable-responsive">
                    <table class="table table-condensed table-striped dataTable-client">
                        <thead>
                            <tr> 
                                <th>{{ Lang::label('SL No.') }}</th>
                                <th>{{ Lang::label('Send To') }}</th>
                                <th>{{ Lang::label('Message') }}</th>
                                <th>{{ Lang::label('Created at') }}</th>
                                <th>{{ Lang::label('Updated at') }}</th>
                                <th>{{ Lang::label('Status') }}</th>
                            </tr>
                        </thead> 
                        <tfoot>
                            <tr> 
                                <th>{{ Lang::label('SL No.') }}</th>
                                <th>{{ Lang::label('Send To') }}</th>
                                <th>{{ Lang::label('Message') }}</th>
                                <th>{{ Lang::label('Created at') }}</th>
                                <th>{{ Lang::label('Updated at') }}</th>
                                <th>{{ Lang::label('Status') }}</th>
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
        ajax: "{{ url('operator/sms/data') }}",
        columns: [  
            {data: 'serial_no', name: 'serial_no', searchable: false},
            {data: 'to',  name: 'to'},
            {data: 'message',  name: 'message'},
            {data: 'created_at',  name: 'created_at'},
            {data: 'updated_at',  name: 'updated_at'},
            {data: 'status', name: 'status'}, 
        ],
    });
});
</script>
@endsection
