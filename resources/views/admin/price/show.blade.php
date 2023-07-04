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
                        <a href="{{ url('admin/price/edit/'.request()->segment(4)) }}" class="btn btn-sm btn-warning waves-effect">
                            <i class="material-icons">edit</i>
                            <span>{{ Lang::label('Edit Price') }}</span>
                        </a>
                    </li>  
                    <li>
                        <a href="{{ url('admin/price/new') }}" class="btn btn-sm btn-success waves-effect">
                            <i class="material-icons">add</i>
                            <span>{{ Lang::label('New Price') }}</span>
                        </a>
                    </li>  
                    <li>
                        <a href="{{ url('admin/price/list') }}" title="Prices" class="btn btn-sm btn-primary waves-effect">
                            <i class="material-icons">list</i>
                            <span>{{ Lang::label('Prices') }}</span>
                        </a> 
                    </li>  
                </ul>
            </div>  

            <div class="body"> 
                <div class="xtable-responsive">
                    <table class="table table-condensed table-striped dataTable">
                        <thead>
                            <tr>
                                <th>{{ Lang::label('SL No.') }}</th>
                                <th>{{ Lang::label('Parking Zone') }}</th>
                                <th>{{ Lang::label('Vehicle Type') }}</th>
                                <th>{{ Lang::label('Time') }}(s)</th>
                                <th>{{ Lang::label('Unit') }}(s)</th>
                                <th>{{ Lang::label('Price') }}(s)</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>{{ Lang::label('SL No.') }}</th>
                                <th>{{ Lang::label('Parking Zone') }}</th>
                                <th>{{ Lang::label('Vehicle Type') }}</th>
                                <th>{{ Lang::label('Time') }}(s)</th>
                                <th>{{ Lang::label('Unit') }}(s)</th>
                                <th>{{ Lang::label('Price') }}(s)</th>
                            </tr>
                        </tfoot> 
                        <tbody>
                            @if(!empty($prices))
                                @foreach($prices as $price)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{ $price->place_name }}</td>
                                    <td>{{ $price->vehicle_type }}</td>
                                    <td>{{ $price->time }}</td>
                                    <td>{{ $price->unit }}</td>
                                    <td>{{ $price->price }}</td>
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
<!-- #END# Exportable Table -->
@endsection

@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
    $('.dataTable').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        pageLength: 25, // default records per page
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
    });
});
</script>
@endsection
