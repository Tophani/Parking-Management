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
                <div class="table-responsivex">
                    {!! Form::open(['url' => 'admin/report', 'method'=>'get', 'class' => 'form-validation']) !!} 
                    <div class="row">
                        <div class="col-sm-3 col-xs-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input name="start_date" type="text" class="form-control datepicker" placeholder="{{ Lang::label('Start Date') }}" value="{{ $input->start_date }}" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3 col-xs-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input name="end_date" type="text" class="form-control datepicker" placeholder="{{ Lang::label('End Date') }}" value="{{ $input->end_date }}" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3 col-xs-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <?php
                                        $payBookList = [
                                            'all' => Lang::label('All Booking'),
                                            'active' => Lang::label('Active Booking'),
                                            'today' => Lang::label('Today\'s Booking'),
                                            'paid' => Lang::label('Paid Booking'), 
                                            'not_paid' => Lang::label('Unpaid Booking')  
                                        ];
                                    ?>
                                    {{ Form::select('booking_type', $payBookList, $input->booking_type, ['class'=>'form-control']) }} 
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3 col-xs-6">
                            <div class="form-group">
                                <div class="form-line">
                                    {{ Form::select('place_id', $placeList,  $input->place_id, ['class'=>'form-control', 'id'=>'place_id', 'placeholder'=>Lang::label("Select Parking Zone")]) }} 
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3 col-xs-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <?php
                                        $filter = [
                                            'booking_id' => Lang::label('Booking ID'),
                                            'client_id' => Lang::label('Client ID'),
                                            'space' => Lang::label('Space'),
                                        ]; 
                                    ?>
                                    {{ Form::select('filter_type', $filter,  $input->filter_type, ['class'=>'form-control', 'placeholder' => Lang::label("Select Filter Type"), 'id'=>'filterOption']) }} 
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3 col-xs-6">
                            <div class="form-group">
                                <div class="form-line">
                                    <input name="search" type="text" class="form-control" placeholder="{{ Lang::label('Search') }}" value="{{ $input->search }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3 col-xs-6">
                            <button type="submit" class="btn btn-success btn-lg waves-effect">{{ Lang::label('Search') }}</button>
                        </div>
                    </div>
                    {!! Form::close() !!}

                    <table class="table table-condensed table-striped dataTable-report">
                        <thead>
                            <tr>
                                <th>{{ Lang::label('SL No.') }}</th>
                                <th>{{ Lang::label('ID No.') }}</th>
                                <th>{{ Lang::label('Parking Zone') }}</th>
                                <th>{{ Lang::label('Space') }}</th>
                                <th>{{ Lang::label('Client ID') }}</th>
                                <th>{{ Lang::label('Vehicle Licence') }}</th>
                                <th>{{ Lang::label('Price') }}</th>
                                <th>{{ Lang::label('Vat') }}</th>
                                <th>{{ Lang::label('Fine') }}</th>
                                <th>{{ Lang::label('Discount') }}</th>
                                <th>{{ Lang::label('Arrival Time') }}</th>
                                <th>{{ Lang::label('Departure Time') }}</th>
                                <th>{{ Lang::label('Status') }}</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @if(!empty($bookings))
                                @foreach($bookings as $booking)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{ $booking->id_no }}</td>
                                    <td><a href="{{ url('admin/place/show/'.$booking->place_id) }}" target="_blank">{{ $booking->place_name }}</a></td>
                                    <td>{{ $booking->space }}</td>
                                    <td><a href="{{ url('admin/client/profile/'.$booking->client_id_no) }}" target="_blank">{{ $booking->client_id_no }}</a></td>
                                    <td>{{ $booking->vehicle_licence }}</td>
                                    <td>{{ $booking->net_price }}</td>
                                    <td>{{ $booking->vat }}</td>
                                    <td>{{ $booking->fine }}</td>
                                    <td>{{ $booking->discount }}</td>
                                    <td>{{ $booking->arrival_time }}</td>
                                    <td>{{ $booking->departure_time }}</td> 
                                    <td>
                                        @if($booking->booking_status)
                                            <label class="label label-success">{{  Lang::label('Released') }}</label>
                                        @else
                                            <label class="label label-warning">{{  Lang::label('Active') }}</label>
                                        @endif
                                        @if($booking->payment_type)
                                            <label class="label label-success">{{  Lang::label('Paid') }}</label>
                                        @else
                                            <label class="label label-danger">{{  Lang::label('Not Paid') }}</label>
                                        @endif 
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody> 
                    </table>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="pull-right">
                    {{ $bookings->links() }}
                </div>
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
$(document).ready(function() 
{ 
    //Booking Datatable
    $('.dataTable-report').DataTable({
        dom: 'Bfrt',
        responsive: false, 
        pageLength: 25, // default records per page
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
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
            };

            //total price
            totalPrice = api.column(6).data().reduce(function(a,b){return intVal(a) + intVal(b); },0);
            //total Vat
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
});
</script>
@endsection
