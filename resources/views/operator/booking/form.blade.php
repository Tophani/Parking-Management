@extends('operator.template')

@section('content')
<div class="row clearfix">
 
    <div class="col-sm-6">
        <div class="card"> 
            <div class="header">
                <h2>{{ $title }}</h2>
                <ul class="header-dropdown m-r--5">  
                    <li>
                        <button type="button" data-toggle="modal" data-target="#priceListModal" class="btn btn-sm btn-success">
                            <i class="material-icons">monetization_on</i>
                            <span>{{ Lang::label('Prices') }}</span>
                        </button>
                    </li>  
                    <li>
                        <a href="{{ url('operator/booking/list') }}" class="btn btn-sm btn-primary waves-effect">
                            <i class="material-icons">list</i>
                            <span>{{ Lang::label('Bookings') }}</span>
                        </a> 
                    </li>  
                </ul>
            </div> 
            
            <div class="body">
                <div id="output"></div>

                <div class="input-group">
                    <label for="place_id">{{ Lang::label("Select Parking Zone") }} *</label>
                    <div class="form-line  {{ $errors->has('place_id') ? 'error focused' : '' }}">
                        {{ Form::select('', $placeList,  null, ['class'=>'form-control bookingPeriod', 'id'=>'place_id', 'placeholder'=>Lang::label("Select Parking Zone")]) }} 
                    </div>
                    @if ($errors->has('place_id'))
                        <label class="error">{{ $errors->first('place_id') }}</label>
                    @endif 
                </div>  

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="place_id">{{ Lang::label("Vehicle Type") }} *</label>
                            <div class="form-line  {{ $errors->has('vehicle_type_id') ? 'error focused' : '' }}">
                                {{ Form::select('vehicle_type_id', $vehicleTypeList,  null, ['class'=>'form-control bookingPeriod', 'id'=>'vehicle_type_id', 'placeholder'=>Lang::label('Select Option'), 'required'=>'required']) }} 
                            </div>
                            @if ($errors->has('vehicle_type_id'))
                                <label class="error">{{ $errors->first('vehicle_type_id') }}</label>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="price_id">{{ Lang::label("Period") }} *</label>
                            <div class="form-line  {{ $errors->has('price_id') ? 'error focused' : '' }}">
                                {{ Form::select('price_id', $priceList,  null, ['class'=>'form-control findScheduleAndPrice', 'id'=>'price_id', 'placeholder'=>Lang::label('Select'), 'required'=>'required']) }} 
                            </div>
                            @if ($errors->has('price_id'))
                                <label class="error">{{ $errors->first('price_id') }}</label>
                            @endif
                        </div>
                    </div>   
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <label for="arrival_time">{{ Lang::label("Arrival Time") }} *</label>
                            <div class="form-line  {{ $errors->has('arrival_time') ? 'error focused' : '' }}">
                                <input name="" type="text" id="arrival_time" class="form-control datetimepicker findScheduleAndPrice" placeholder="{{ Lang::label('Arrival Time') }}" value="{{ (old('arrival_time')?old('arrival_time'):date('Y-m-d H:i')) }}" style="height:27px">
                            </div>
                            @if ($errors->has('arrival_time'))
                                <label class="error">{{ $errors->first('arrival_time') }}</label>
                            @endif 
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="input-group departure_section hidex"> 
                            <label for="departure_time">{{ Lang::label('Departure Time') }} *</label>
                            <div class="form-line {{ $errors->has('departure_time') ? 'error focused' : '' }}">
                                <input type="text" id="departure_time" class="form-control" placeholder="{{ Lang::label('Departure Time') }}" disabled readonly style="height:27px">
                            </div>
                            @if ($errors->has('departure_time'))
                                <label class="error">{{ $errors->first('departure_time') }}</label>
                            @endif
                        </div> 
                    </div>
                </div>
 

                <div class="input-group">
                    <div class="row"> 
                        <div class="col-xs-4"><strong>{{ Lang::label('Select Space') }} *</strong></div>
                        <div class="col-xs-8 text-right">
                            <span class="label label-sm label-info">{{ Lang::label('Selected') }}</span>
                            <span class="label label-sm label-success">{{ Lang::label('Available') }}</span>
                            <span class="label label-sm label-warning">{{ Lang::label('Occupied') }}</span>
                        </div>
                        <div class="col-xs-12"> 
                            <div class="{{ $errors->has('space') ? 'bg-danger' : 'bg-success' }} reserve" id="scheduleSpaces">
                            </div>
                            @if ($errors->has('space'))
                                <label class="error">{{ $errors->first('space') }}</label>
                            @endif
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>


    <div class="col-sm-6">
        <div class="card">
            <div class="body">   
            <!-- Advanced Form Example With Validation -->
            {!! Form::open(['url' => 'operator/booking/place_order', 'class' => 'form-validation', 'id'=> 'bookingFrm']) !!} 

                {{ Form::hidden('place_id') }}
                {{ Form::hidden('price_id') }}
                {{ Form::hidden('arrival_time') }}
                {{ Form::hidden('departure_time') }}
                {{ Form::hidden('space') }} 
                {{ Form::hidden('promocode_id') }} 
                {{ Form::hidden('booking_period') }} 

                <div class="input-group" style="margin-bottom:0">
                    <div class="row">
                        <div class="col-xs-5">
                            <label for="client_id_no">{{ Lang::label('Client ID') }} *</label>
                            <div class="form-line  {{ $errors->has('client_id_no') ? 'error focused' : '' }}">
                                <input name="client_id_no" type="text" id="client_id_no" class="form-control calculate" placeholder="{{ Lang::label('Client ID') }}" value="{{ old('client_id_no') }}" style="height:27px">
                            </div>
                            <span class="text-danger"></span>
                            @if ($errors->has('client_id_no'))
                                <label class="error">{{ $errors->first('client_id_no') }}</label>
                            @endif
                        </div>
                        <div class="col-xs-1">
                            <label for="newClient">&nbsp;</label>
                            <div>
                                <button type="button" data-toggle="modal" data-target="#newClientModal" id="newClient"  class="btn btn-primary">+</button>
                            </div>
                        </div>
                        <div class="col-xs-6">  
                            <label for="client_name">{{ Lang::label('Client Name') }} </label>
                            <div class="form-line {{ $errors->has('client_name') ? 'error focused' : '' }}">
                                <input type="text" id="client_name" class="form-control calculate" placeholder="{{ Lang::label('Client Name') }}" disabled readonly style="height:27px">
                            </div>
                            <span class="text-success sr-only"></span>
                            @if ($errors->has('client_name'))
                                <label class="error">{{ $errors->first('client_name') }}</label>
                            @endif 
                        </div> 
                    </div>
                </div> 

                <!-- NET PRICE -->
                <div class="input-group" style="margin-bottom:0">
                    <div class="row"> 
                        <div class="col-xs-6">  
                            <label for="vehicle_licence">{{ Lang::label("Vehicle licence") }} *</label>
                            <div class="form-line  {{ $errors->has('vehicle_licence') ? 'error focused' : '' }}">
                                {{ Form::select('vehicle_licence', [],  null, ['class'=>'form-control no-select', 'id'=>'vehicle_licence', 'placeholder'=>Lang::label('Select Option')]) }} 
                            </div>
                            @if ($errors->has('vehicle_licence'))
                                <label class="error">{{ $errors->first('vehicle_licence') }}</label>
                            @endif
                        </div> 
                        <div class="col-xs-6">
                            <label for="net_price">{{ Lang::label('Net Price') }} </label>
                            <div class="form-line  {{ $errors->has('net_price') ? 'error focused' : '' }}">
                                <input name="net_price" type="text" id="net_price" class="form-control" placeholder="{{ Lang::label('Net Price') }}" value="0.0" autocomplete="off" readonly style="height:27px">
                            </div>
                            <span class="text-success sr-only"></span>
                            @if ($errors->has('net_price'))
                                <label class="error">{{ $errors->first('net_price') }}</label>
                            @endif 
                        </div> 
                    </div>
                </div>

                <div class="input-group" style="margin-bottom:0">
                    <div class="row"> 
                        <div class="col-xs-6">
                            <label for="promocode">{{ Lang::label('Promo Code') }} </label>
                            <div class="form-line  {{ $errors->has('promocode') ? 'error focused' : '' }}">
                                <input type="text" name="promocode" id="promocode" class="form-control" placeholder="{{ Lang::label('Promo Code') }}" value="{{ old('promocode') }}" autocomplete="off" style="height:27px">
                            </div>
                            <span class="text-success sr-only"></span>
                            @if ($errors->has('promocode'))
                                <label class="error">{{ $errors->first('promocode') }}</label>
                            @endif 
                        </div>
                        <div class="col-xs-6">
                            <label for="discount">{{ Lang::label('Discount') }}</label>
                            <div class="form-line  {{ $errors->has('discount') ? 'error focused' : '' }}">
                                <input name="discount" type="text" id="discount" class="form-control" placeholder="{{ Lang::label('Discount') }}" value="0.0" autocomplete="off" style="height:27px" readonly>
                            </div>
                            <span class="text-success sr-only"></span>
                            @if ($errors->has('discount'))
                                <label class="error">{{ $errors->first('discount') }}</label>
                            @endif
                        </div> 
                    </div>
                </div>

                <!-- VAT -->
                <div class="input-group" style="margin-bottom:0">
                    <div class="row">  
                        <div class="col-xs-6">
                            <label>{{ Lang::label('Vat') }} = {{ (($setting->vat_type==1)?($setting->vat . '%  of ' . Lang::label('Net Price')): ($setting->vat . ' ' . $setting->currency)) }}</label> 
                        </div> 
                        <div class="col-xs-6">
                            <label for="vat">{{ Lang::label('Vat') }}</label>
                            <div class="input-group">
                                <div class="form-line">
                                    <input name="vat" type="text" id="vat" class="form-control" placeholder="{{ Lang::label('Vat') }}" value="0.0" style="height:27px" readonly>
                                </div> 
                            </div> 
                        </div> 
                    </div>
                </div>

                <!-- GRAND TOTAL -->
                <div class="input-group" style="margin-bottom:0">
                    <div class="row"> 
                        <div class="col-xs-6">
                            <label>{{ Lang::label('Fine') }} = {{ $setting->fine }} {{ (($setting->fine_type==1)?'% of '. Lang::label('Net Price'). ' + '. Lang::label('Extra Time Price'):$setting->currency) }}</label>
                            <p>(Depend on late departure time & tolerance 30 minutes) </p> 
                        </div> 
                        <div class="col-xs-6">
                            <label for="total_price">{{ Lang::label('Total Price') }} </label>
                            <div class="form-line  {{ $errors->has('total_price') ? 'error focused' : '' }}">
                                <input name="total_price" type="text" id="total_price" class="form-control" placeholder="{{ Lang::label('Total Price') }}" value="0.0" autocomplete="off" style="height:27px" readonly>
                            </div>
                            <span class="text-success sr-only"></span>
                            @if ($errors->has('total_price'))
                                <label class="error">{{ $errors->first('total_price') }}</label>
                            @endif 
                        </div> 
                    </div>
                </div>

                <div class="input-group"  style="margin-bottom:0">
                    <label for="note">{{ Lang::label('Note') }}</label> 
                    <div class="form-line  {{ $errors->has('note') ? 'error focused' : '' }}">
                        <textarea name="note" type="text" id="note" class="form-control" placeholder="Note">{{ old('note') }}</textarea>
                    </div>
                    @if ($errors->has('note'))
                        <label class="error">{{ $errors->first('note') }}</label>
                    @endif 
                </div>   
 
                <div class="input-group">
                    <div class="col-sm-6">
                        <input id="payment_type" name="payment_type" type="checkbox" value="1"> 
                        <label for="payment_type"><strong>{{ Lang::label('Paid') }}</strong></label>
                    </div>
                    <div class="col-sm-6 text-right">
                        <input type="reset" class="btn btn-warning" value="{{ Lang::label('Reset') }}">
                        <button type="submit" class="btn btn-success waves-effect">{{ Lang::label('Booking Now') }}</button>
                    </div>
                </div>
            {!! Form::close() !!}
            <!-- #END# Advanced Form Example With Validation -->   
            </div>


            <div class="card"> 
                <div class="body">  
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="input-group">
                                <div class="form-line">
                                    <input type="hidden" id="start" value="1">
                                    <input type="text" class="form-control" id="pac-input" placeholder="Select your location">
                                </div> 
                            </div> 
                            <p id="success" class="sr-only alert alert-success"></p>
                            <p id="error" class="sr-only alert alert-danger"></p>
                        </div>
                        <div class="col-xs-8">
                            <div id="map" style="width:100%;height:220px"></div>
                        </div>
                    </div> 
                </div>
            </div>

        </div>
    </div>
  
</div>




<!-- PRICE LIST -->
<div class="modal fade" id="priceListModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="priceListModalLabel">
                    <strong>{{ Lang::label("Prices") }}</strong>
                    <button type="button" class="pull-right btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                </h4>
            </div>
            <div class="modal-body bg-info"></div> 
        </div>
    </div>
</div>
 
 

<!-- NEW CLIENT -->
<div class="modal fade" id="newClientModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="newClientModalLabel">
                    {{ Lang::label("New Client") }}
                    <button type="button" class="pull-right btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                </h4>
            </div>
            <div class="modal-body">
                <div class="alert sr-only" id="output2"></div>
                {!! Form::open(['url' => 'operator/booking/createClient', 'class' => 'form-validation', 'files' => true, 'id'=>'frmClient']) !!}
                <div class="row">
                    <div class="col-sm-6">
                        <label for="name">{{ Lang::label("Name") }}*</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('name') ? 'error focused' : '' }}">
                                <input name="name" type="text" id="name" class="form-control" placeholder="{{ Lang::label('Enter Name') }}" value="{{ old('name') }}">
                            </div>
                            @if ($errors->has('name'))
                                <label class="error">{{ $errors->first('name') }}</label>
                            @endif
                        </div>

                        <label for="mobile">{{ Lang::label("Phone / Mobile") }}*</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('mobile') ? 'error focused' : '' }}">
                                <input name="mobile" type="text" id="mobile" class="form-control" placeholder="{{ Lang::label('Enter Phone or Mobile No.') }}" value="{{ old('mobile') }}">
                            </div>
                            @if ($errors->has('mobile'))
                                <label class="error">{{ $errors->first('mobile') }}</label>
                            @endif
                        </div>

                        <label for="email">{{ Lang::label("Email") }} *</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('email') ? 'error focused' : '' }}">
                                <input name="email" type="text" id="email" class="form-control" placeholder="{{ Lang::label('Enter Email Address') }}" value="{{ old('email') }}">
                            </div>
                            @if ($errors->has('email'))
                                <label class="error">{{ $errors->first('email') }}</label>
                            @endif
                        </div>
                        
                        <label for="password">{{ Lang::label("password") }} *</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('password') ? 'error focused' : '' }}">
                                <input name="password" type="password" id="password" class="form-control" placeholder="{{ Lang::label('Password') }}" value="{{ old('password') }}">
                            </div>
                            @if ($errors->has('password'))
                                <label class="error">{{ $errors->first('password') }}</label>
                            @endif
                        </div>

                        <label for="address">{{ Lang::label("Address") }}</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('address') ? 'error focused' : '' }}">
                                <textarea name="address" type="text" id="address" class="form-control" placeholder="{{ Lang::label('Enter Address') }}">{{ old('address') }}</textarea>
                            </div>
                            @if ($errors->has('address'))
                                <label class="error">{{ $errors->first('address') }}</label>
                            @endif
                        </div>
                    </div>


                    <div class="col-sm-6">
                        <label for="licence">{{ Lang::label("Vehicle Licence") }} *</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('licence') ? 'error focused' : '' }}">
                                <input name="licence" type="text" id="licence" class="form-control" placeholder="{{ Lang::label('Enter Vehicle Licence No.') }}" value="{{ old('licence') }}">
                            </div>
                            @if ($errors->has('licence'))
                                <label class="error">{{ $errors->first('licence') }}</label>
                            @endif
                        </div>

                        <label for="photo">{{ Lang::label("Vehicle Photo") }}</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('photo') ? 'error focused' : '' }}">
                                <input name="photo" type="file" id="photo" class="form-control">
                                <input name="old_photo" type="hidden" value="{{ old('photo') }}">
                            </div>
                            @if ($errors->has('photo'))
                                <label class="error">{{ $errors->first('photo') }}</label>
                            @endif
                        </div>

                        <label for="color">{{ Lang::label('Color') }}</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('color') ? 'error focused' : '' }}">
                                <input name="color" type="text" id="color" class="form-control" placeholder="{{ Lang::label('Enter Color') }}" value="{{ old('color') }}">
                            </div>
                            @if ($errors->has('color'))
                                <label class="error">{{ $errors->first('color') }}</label>
                            @endif
                        </div> 

                        <label for="note">{{ Lang::label("Note") }}</label>
                        <div class="form-group">
                            <div class="form-line  {{ $errors->has('note') ? 'error focused' : '' }}">
                                <textarea name="note" type="text" id="note" class="form-control" placeholder="{{ Lang::label('Note About Vehicle') }}">{{ old('note') }}</textarea>
                            </div>
                            @if ($errors->has('note'))
                                <label class="error">{{ $errors->first('note') }}</label>
                            @endif
                        </div>

                        <div class="col-sm-12">
                            <label for="status">{{ Lang::label("Status") }}</label>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="switch {{ $errors->has('status') ? 'error focused' : '' }}">
                                            <label>
                                                <input name="status" type="checkbox" checked="" value="1">
                                                <span class="lever"></span>
                                            </label>
                                            @if ($errors->has('status'))
                                                <label class="error">{{ $errors->first('status') }}</label>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-8 text-right">
                                        <button type="reset" class="btn btn-danger waves-effect">{{ Lang::label("Reset") }}</button>
                                        <button type="submit" class="btn btn-success waves-effect">{{ Lang::label("Save") }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                {!! Form::close() !!}
            </div> 
        </div>
    </div>
</div>
@endsection


@section('scripts')
<!-- JavaScript -->
<script type="text/javascript">
$(document).ready(function()
{  

    /*
    |____________________________________________________________
    |
    | Booking Submit
    |____________________________________________________________
    |
    */
    var bookingFrm = $("#bookingFrm");
    bookingFrm.on('submit', function(e)
    {
        $("#output").html("");
        e.preventDefault(); 

        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            dataType: "JSON",
            data: bookingFrm.serialize(),
            beforeSend: function() {
                 $(".loader").fadeOut("slow"); 
            },
            success: function(data)
            { 
                $(".loader").fadeOut("hide"); 

                if (data.status)
                {
                    var restorepage = $('body').html();
                    $('body').empty().html(data.result);
                    window.print();
                    $('body').html(restorepage); 
                    history.go(0);
                }
                else
                {
                    $("#output").html("<div class='alert alert-danger alert-dismissible'><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" + data.exception + "</div>");
                }
            },
            error:function()
            {
                alert('failed');
            }
        }); 

    });


    /*
    |____________________________________________________________
    |
    | Get Price List
    |____________________________________________________________
    |
    */
    
    $('#priceListModal').on('show.bs.modal', function (event) 
    {
        var modal = $(this);
        modal.find('.modal-header strong').text($("select#place_id option:selected").text()+" - Prices");
        var id = $("#place_id").val();  
        if (id)
        {
            $.ajax({
                url: '{{ URL::to("operator/booking/getPriceList") }}',
                type: 'post',
                dataType: 'json', 
                data:{place_id: id},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(data)
                {
                    var body = "<table width='100%' border='1' class='text-center'>"+
                            "<thead><tr>"+
                            "<th class='text-center'>Vehicle Type</th>"+
                            "<th class='text-center'>Time Unit(s)</th>"+
                            "<th class='text-center'>Price(s)</th>"+
                            "</tr></thead>";

     
                        $.each(data, function(i, v)
                        {  
                            body += "<tbody>"+
                                    "<tr>"+
                                        "<td>"+v.vehicle_type+"</td>"+
                                        "<td>"+v.time+" "+v.unit+"</td>"+
                                        "<td>{{ $setting->currency }}"+v.price+"</td>"+
                                    "</tr>"+ 
                                "</tbody>";
                        });
                        body += "</table>";
     
                    modal.find('.modal-body').html(body); 
                },
                error:function(xhr)
                {
                    modal.find('.modal-body').html(xhr);
                }
            });
        }
        else
        {
            modal.find('.modal-body').html("Please select a parking zone!");
        }  
    }) 


    /*
    |____________________________________________________________
    |
    | Select parking slot
    |____________________________________________________________
    |
    */
    $('body').on('click', '.res-slot', function()
    { 
        var slot = $(this); 
        if (slot.attr('data-item') != "selected") 
        {
            $('.res-slot').removeClass('selected').attr('data-item',''); 
            slot.addClass('selected').attr('data-item','selected'); 
            $("input[name=space]").val(slot.find('.title').text()); 
        }  
        else if (slot.attr('data-item') == "selected")  
        {
            slot.removeClass('selected').attr('data-item','');  
            $("input[name=space]").val(""); 
        }  
    }); 
 


    /*
    |____________________________________________________________
    |
    | Get schedule and price
    |____________________________________________________________
    |
    */
    var place_id        = $("#place_id");
    var price_id        = $("#price_id");
    var arrival_time    = $("#arrival_time");
    var departure_time  = $("#departure_time");
    var vehicle_type_id = $("#vehicle_type_id");
    $('body').on('change', '.findScheduleAndPrice', function()
    { 
        $("#output").removeClass('sr-only alert-danger alert').html('');
        //reseting previous value
        $("#scheduleSpaces").html("");
        $("#departure_time").val("");
        // set inputs
        $("input[name=place_id]").val(place_id.val());
        $("input[name=price_id]").val(price_id.val());
        $("input[name=arrival_time]").val(arrival_time.val());
        $("input[name=net_price]").val("");
        $("input[name=vat]").val(""); 
        $("input[name=booking_period]").val(price_id.find('option:selected').text()); 
        $("input[name=vehicle_type_id]").val(vehicle_type_id.val());

        if(place_id.val() && price_id.val() && vehicle_type_id.val() && arrival_time.val())
        {   
            //ajax call
            $.ajax({
                url: '{{ URL::to("operator/booking/findScheduleAndPrice") }}',
                type: 'post',
                dataType: 'json', 
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'place_id'       : place_id.val(),
                    'price_id'       : price_id.val(), 
                    'arrival_time'   : arrival_time.val(), 
                    'vehicle_type_id': vehicle_type_id.val(), 
                },
                success: function(data)
                {  
                    // show schedule
                    if (data.booking_status)
                    {
                        $("#departure_time").val(data.departure_time);
                        $("#scheduleSpaces").html(data.spaces);
                        // set form inputs
                        $("input[name=net_price]").val(data.price);
                        $("input[name=vat]").val(data.vat);
                        $("input[name=departure_time]").val(data.departure_time);
                    }
                    else
                    {

                    }

                    //calculate total price
                    calculateTotalPrice();
                },
                error: function(xhr)
                {
                    alert('failed...');
                }
            });
        } 
    }); 

    /*
    |____________________________________________________________
    |
    | Get discount by promocode
    |____________________________________________________________
    |
    */ 
    var promocode = $('#promocode');
    promocode.on('keyup', function()
    { 
        $.ajax({
            url: '{{ URL::to("operator/booking/getDiscount") }}',
            type: 'post',
            dataType: 'json', 
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: { 
                'promocode': $(this).val(), 
                'arrival_time': $('#arrival_time').val() 
            },
            success: function(data)
            {
                if (data.status)
                {
                    $('input[name=promocode_id]').val(data.promocode_id);
                    $('input[name=discount]').val(data.discount);
                    promocode.parent().next().text(data.success).addClass('text-success').removeClass('text-danger sr-only');
                }
                else 
                {
                    $('input[name=promocode_id]').val("");
                    $('input[name=discount]').val("0");
                    promocode.parent().next().text(data.exception).addClass('text-danger').removeClass('text-success sr-only');
                }
                //calculate total price
                calculateTotalPrice();
            },
            error: function(xhr)
            {
                alert('failed...');
            }
        });
    });


    /*
    |____________________________________________________________
    |
    | Check whether client exists or not
    |____________________________________________________________
    |
    */
    $("#client_id_no").on('keyup change', function(e){
        e.preventDefault();
        var that = $(this);

        if(that.val())
        {  
            $.ajax({
                url: '{{ URL::to("operator/booking/checkClientID") }}',
                type: 'post',
                dataType: 'json', 
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: { 
                    'client_id_no': that.val() 
                },
                success: function(data)
                {
                    if (data.status)
                    {
                        $("#client_name").val(data.name);
                        $("#vehicle_licence").html(data.vehicles);
                        that.parent().next().text("");
                    }
                    else 
                    {
                        $("#client_name").val("");
                        $("#vehicle_licence").html("");
                        that.parent().next().html(data.exception);
                    }
                },
                error: function(xhr)
                {
                    alert('failed...');
                }
            });
        }
    });

    /*
    |____________________________________________________________
    |
    | Create new client
    |____________________________________________________________
    |
    */
    var output2 = $("#output2");
    var form = $("#frmClient"); 
    form.on('submit', function(e) 
    {
        e.preventDefault(); 
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            dataType: 'json',
            data:  new FormData($(this)[0]),
            contentType: false,  
            cache: false,  
            processData: false,     
            success: function(data)
            { 
                if (data.status)
                {
                    output2.removeClass('sr-only').removeClass('alert-danger').addClass('alert-success').html(data.success);
                    $("input[name=client_id_no]").val(data.client_id_no);
                    $("#client_name").val(data.client_name);
                    $("#vehicle_licence").html(data.vehicles);
                    $('#newClientModal').modal('hide');
                } 
                else 
                {
                    output2.removeClass('sr-only').removeClass('alert-success').addClass('alert-danger').html(data.exception);
                    $("#vehicle_licence").html("");
                } 
            },
            error: function(data)
            {
                alert('failed...');
            }

        });
    });
  
    /*
    |____________________________________________________________
    |
    | set dynamic value to time & price 
    |____________________________________________________________
    |
    */
    $('body').on("change", ".bookingPeriod", function(){
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            url: '{{ url("operator/booking/getZoneAndVehicleWisePriceList") }}',
            // dataType: 'json',
            type    : 'post',
            data    : {
                place_id: $("#place_id").val(),
                vehicle_type_id: $("#vehicle_type_id").val()
            },
            success: function(data)
            {
                var options = "<option value=''>Select</option>";
                $.each(data, function(i, v){
                    options += "<option value='"+i+"'>"+v+"</option>";
                });
                $('#price_id').html(options);
                $('#price_id').trigger('change');
            },
            error: function(xhr) 
            {
                console.log("failed");
            }
        });     
    });
    
}); 
 

    /*
    |____________________________________________________________
    |
    | Calcucalate total price 
    |____________________________________________________________
    |
    */
    function calculateTotalPrice()
    {
        var net_price = $("input[name=net_price]").val();
        var discount  = $("input[name=discount]").val();
        var vat       = $("input[name=vat]").val(); 
        var total = parseFloat(net_price)-parseFloat(discount)+parseFloat(vat);
        $("input[name=total_price]").val(total.toFixed(1));
    }

    /*
    |____________________________________________________________
    |
    | GOOGLE MAP 
    |____________________________________________________________
    |
    */
    // settings from database  
    var map, marker, infowindow, lastMarker, request, title, directionsDisplay;
    var errorStatus   = document.getElementById("error");
    var successStatus = document.getElementById("success"); 
    var config = { 
        zoom: parseInt("{{ ($setting->map_zoom?$setting->map_zoom:15) }}"),
        lat: parseFloat("{{ ($setting->latitude?$setting->latitude:1) }}"),
        lng: parseFloat("{{ ($setting->longitude?$setting->longitude:1) }}"), 
        title: ("{{ ($setting->title?$setting->title:'Demo Application') }}"),  
    };  

    function initMap() 
    {   
        map = new google.maps.Map(document.getElementById('map'), {
            zoom  : config.zoom,
            center: {lat: config.lat, lng: config.lng},
            mapTypeControl: true,
            navigationControl: true,
            mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
            navigationControlOptions: {style: google.maps.NavigationControlStyle.ZOOM_PAN},
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }); 

        marker = new google.maps.Marker({
            position: new google.maps.LatLng(config.lat, config.lng),
            map: map,
            title: config.title
        });

        // add custom label
        var content = '<strong>'+config.title+'</strong>';    
        var infowindow = new google.maps.InfoWindow();
        infowindow.setContent(content);
        infowindow.open(map,marker);
        google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
            return function() {
                infowindow.setContent(content);
                infowindow.open(map,marker);
            };
        })(marker,content,infowindow));   
        //------------------------------------------------------------
        // HTML5 geolocation. 
        if (navigator.geolocation) 
        {
            navigator.geolocation.getCurrentPosition(function geolocationSuccess(position) {
                pos = {
                  lat: position.coords.latitude,
                  lng: position.coords.longitude
                };  
                //change direction
                myDirection(position.coords.latitude+', '+position.coords.longitude);

                //display address
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({latLng: pos}, function(results, status) {
                    if (google.maps.GeocoderStatus.OK) {
                        document.getElementById("pac-input").value = results[0].formatted_address;
                    }  
                });

                //display location
                myLocationWithMarker(pos); 
            }, function() {
                errorStatus.innerHTML = 'browser doesn\'t support geolocation'; 
                errorStatus.classList.remove("sr-only"); 
            });
        }  
        //------------------------------------------------------------
        /* autocomplete search*/
        var input = document.getElementById('pac-input');  
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.addListener('place_changed', function() {

          var place = autocomplete.getPlace();
          if (place.geometry) 
          {
            var position = place.geometry.location.lat()+", "+place.geometry.location.lng();

            //display location
            myLocationWithMarker(place.geometry.location);

            //change direction
            myDirection(position);

          } else {
            errorStatus.innerHTML = "No details available for input: '" + place.name + "'";
            errorStatus.classList.remove("sr-only");
          }  
        });
 
        //------------------------------------------------------------
        function myDirection(origin)
        {  
            directionsDisplay = new google.maps.DirectionsRenderer({
                polylineOptions: {
                  strokeColor: "red"
                }
            });
            directionsDisplay.setMap(map);

            var request = {
                origin      : origin,
                destination : config.lat+', '+config.lng,
                travelMode  : google.maps.TravelMode.DRIVING
            };
            var directionsService = new google.maps.DirectionsService(); 

            directionsService.route(request, function(response, status) {
                if (status == google.maps.DirectionsStatus.OK) {

                    var distance = (response.routes[0].legs[0].distance.value/1000).toFixed(2);
  
                    var seconds = parseInt(response.routes[0].legs[0].duration.value, 10);
                    var days     = Math.floor(seconds / (3600*24));
                    var hours    = Math.floor(seconds / 3600);
                    var minutes  = Math.floor((seconds - (hours * 3600)) / 60);
                    var time     = (days?days+' days ':'')+(hours?hours+' hours ':'')+(minutes?minutes+' minutes ':'');

                    // Display the distance and duration:
                    directionsDisplay.setDirections(response); 

                    successStatus.innerHTML = "Approximate distance is "+
                    distance + " kilometers and Approximate time is "+ time;
                    successStatus.classList.remove("sr-only"); 
                }
            });            
        }

        //------------------------------------------------------------
        function myLocationWithMarker(position)
        { 
            //remove previous marker
            if (lastMarker)
            { 
                lastMarker.setMap(null); 
            }  

            lastMarker = new google.maps.Marker({
                position: position,
                map: map,
                zIndex: 99, 
                animation:google.maps.Animation.DROP,
                strokeColor: "red"
            }); 
            map.setCenter(position); 

            // add custom label
            var infowindow = new google.maps.InfoWindow({
                content: '<strong style="color:green;font-weight:bolder">My Location</strong>'
            }); 
            infowindow.open(map, lastMarker); 
        }
    } 
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $setting->map_api_key }}&maptype=roadmap&libraries=places&callback=initMap"></script>
@endsection
