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
                        <button type="button" class="btn btn-sm btn-warning waves-effect" data-toggle="modal" data-target="#infoModal">
                            <i class="material-icons">help_outline</i>
                            <span>{{ Lang::label('Fine') }}</span>
                        </button>
                    </li> 
                    <li>
                        <a href="{{ url('admin/booking/form') }}" class="btn btn-sm btn-success waves-effect">
                            <i class="material-icons">add</i>
                            <span>{{ Lang::label('New Booking') }}</span>
                        </a>
                    </li>   
                    <li>
                        <a href="{{ url('admin/booking/list') }}" class="btn btn-sm btn-primary waves-effect">
                            <i class="material-icons">list</i>
                            <span>{{ Lang::label('Bookings') }}</span>
                        </a> 
                    </li>  
                </ul>
            </div> 

            <div class="body"> 
                <table class="table table-condensed table-striped dataTable-booking">
                    <thead>
                        <tr>
                            <th>{{ Lang::label('SL No.') }}</th>
                            <th>{{ Lang::label('ID No.') }}</th>
                            <th>{{ Lang::label('Parking Zone') }}</th>
                            <th>{{ Lang::label('Space') }}</th>
                            <th>{{ Lang::label('Client ID') }}</th>
                            <th>{{ Lang::label('Vehicle Licence') }}</th>
                            <th>{{ Lang::label('Net Price') }}</th>
                            <th>{{ Lang::label('Vat') }}</th>
                            <th>{{ Lang::label('Fine') }}</th>
                            <th>{{ Lang::label('Discount') }}</th>
                            <th>{{ Lang::label('Arrival Time') }}</th>
                            <th>{{ Lang::label('Departure Time') }}</th>
                            <th>{{ Lang::label('Booking Status') }}</th>
                            <th width="150">{{ Lang::label('Action') }}</th>
                        </tr>
                    </thead> 
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ Lang::label('Net Price') }}</td>
                            <td>{{ Lang::label('Vat') }}</td>
                            <td>{{ Lang::label('Fine') }}</td>
                            <td>{{ Lang::label('Discount') }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td> 
                        </tr>
                    </tfoot> 
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h4 class="modal-title" id="infoModalLabel">{{ Lang::label("Fine") }}</h4>
            </div>
            <div class="modal-body">
                <label>Current {{ Lang::label('Fine') }} = {{ $setting->fine }} {{ (($setting->fine_type==1)?'% of '. Lang::label('Net Price'). ' + '. Lang::label('Extra Time Price'):$setting->currency) }}</label>
                            <p>(Depend on late departure time & tolerance 30 minutes) </p> 
    <pre>
    There are 2 types of Fine Included. 
    * Net Price        = Previous Net Price + Late Price
    1. Percentage Fine = Fine Amount % Net Price 
    2. Fixed/Flat Fine = Fine Amount  

    Suppose, 
    Booking Period 2 Hours = Net Price $20
    Late Time   = 1 Hour
    Fine Amount = 10
    
    Now, Late 1 Hour = Late Price $10
    Net Price   = Previous Net Price $20 + Late Price $10
                = $30

    1. If Fine Value Type is Percentage 
    Then, Fine  = Fine Value 10 Percence of Net Price $30
                = $3 

    2. If Fine Value Type is Fixed/Flat 
    Then, Fine  = Fine Value 10
                = $10 
    </pre> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>




@endsection
 


@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
    //Booking Datatable
    $('.dataTable-booking')
    .DataTable({
        order: [], //reset auto order
        processing: true,
        responsive: true,
        serverSide: true,
        select: true,
        pageLength: 25, // default records per page
        "bInfo": true,
        pagingType: "full_numbers",
        dom: "<'row'<'col-sm-12'i><'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tp", 
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
                text: 'Print Selected',
                exportOptions: {
                    columns: ':visible'
                } 
            },  
            {
                extend: 'print', 
                exportOptions: {
                    columns: ':visible', 
                    modifier: {
                        selected: null
                    }
                },
            },
            {extend: 'colvis'} 
        ], 
        ajax: '{!! url("admin/booking/get-data").'/'.(!empty($type)?$type:null) !!}',
        columns: [ 
            { data: 'serial_no',    name: 'serial_no' }, 
            { data: 'id_no',        name: 'id_no' }, 
            { data: 'place_name',   name: 'place_name' }, 
            { data: 'space',        name: 'space' }, 
            { data: 'client_id_no', name: 'client_id_no' },
            { data: 'vehicle_licence', name: 'vehicle_licence' },
            { data: 'net_price',    name: 'net_price' },
            { data: 'vat',          name: 'vat' },
            { data: 'fine',         name: 'fine' },
            { data: 'discount',     name: 'discount' },
            { data: 'arrival_time', name: 'arrival_time' },
            { data: 'departure_time', name: 'departure_time' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],  
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
            };

            //total price
            totalPrice = api.column(6).data().reduce(function(a,b){return intVal(a) + intVal(b); },0);
            //total vat
            totalVat = api.column(7).data().reduce( function(a,b){ return intVal(a) + intVal(b); },0);
            //total fine
            totalFine = api.column(8).data().reduce( function(a,b){ return intVal(a) + intVal(b); },0);
            //total discount
            totalDiscount = api.column(9).data().reduce( function(a,b){ return intVal(a) + intVal(b); },0);


            $(api.column(5).footer()).html('{{ Lang::label("Total") }}'); 
            $(api.column(6).footer()).html(totalPrice.toFixed(1)); 
            $(api.column(7).footer()).html(totalVat.toFixed(1)); 
            $(api.column(8).footer()).html(totalFine.toFixed(1));
            $(api.column(9).footer()).html(totalDiscount.toFixed(1)); 
            $(api.column(10).footer()).html('Grand Total: '+ (totalPrice+totalVat+totalFine-totalDiscount).toFixed(1)); 
        }

    });


    $("body").on('click', '.tokenAction', function(e)
    {   
        e.preventDefault(); 
        $.ajax({
            url: '{{ url("admin/booking/invoice") }}',
            type: 'get',
            data: {id_no: $(this).data('booking-id')},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:function(data)
            { 
                var restorepage  = $('body').html();
                $('body').empty().html(data);
                window.print();
                $('body').html(restorepage); 
                history.go(0);
            },
            error:function()
            {
                alert('failed');
            }
        });
    });


    $("body").on('click', '.releaseAction', function(e)
    {   
        e.preventDefault(); 
        $.ajax({
            url: '{{ url("admin/booking/release") }}',
            type: 'get',
            data: {id_no: $(this).data('booking-id')},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:function(data)
            { 
                var restorepage  = $('body').html();
                $('body').empty().html(data);
                window.print();
                $('body').html(restorepage); 
                history.go(0);
            },
            error:function()
            {
                alert('failed');
            }
        });
    });


    $("body").on('click', '.paidAction', function(e)
    {   
        e.preventDefault(); 
        $.ajax({
            url: '{{ url("admin/booking/payment_status") }}',
            type: 'get',
            data: {id_no: $(this).data('booking-id')},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:function(data)
            { 
                var restorepage  = $('body').html();
                $('body').empty().html(data);
                window.print();
                $('body').html(restorepage); 
                history.go(0);
            },
            error:function()
            {
                alert('failed');
            }
        });
    });


    $("body").on('click', '.fineAction', function(e)
    {   
        e.preventDefault(); 
        $.ajax({
            url: '{{ url("admin/booking/fine") }}',
            type: 'get',
            data: {id_no: $(this).data('booking-id')},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success:function(data)
            { 
                var restorepage  = $('body').html();
                $('body').empty().html(data);
                window.print();
                $('body').html(restorepage); 
                history.go(0);
            },
            error:function()
            {
                alert('failed');
            }
        });
    });

});

 
</script>
@endsection
