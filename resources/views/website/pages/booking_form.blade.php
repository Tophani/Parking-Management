@extends('website.template')

@section('content')
<!-- Exportable Table -->
<div class="row clearfix">
    <div class="col-sm-12">
        <div class="card mb-2"> 
            <div class="header">
                <h2>{{ $title }}</h2>
                <ul class="header-dropdown m-r--5">   
                    <li>
                        <a href="{{ url('/') }}" class="btn btn-sm btn-success waves-effect">
                            <i class="material-icons">add</i>
                            <span>{{ Lang::label('New Booking') }}</span>
                        </a>
                    </li>    
                </ul>
            </div> 

            <div class="body">

                @if(empty($setting->paypal_client_id) || empty($setting->paypal_secret_key))
                <div class="alert alert-warning">Please add PayPal credentials (Client ID & Secret) to booking a space!</div>
                @endif

                <div id="bookingMessage"></div>

                {!! Form::open(['url' => 'website/booking/place_order', 'class' => 'row', 'id'=> 'bookingFrm']) !!} 
                {{ Form::hidden('place_id', request()->get('place_id')) }}
                {{ Form::hidden('space') }}
                {{ Form::hidden('promocode_id') }}
                {{ Form::hidden('departure_time') }}

                <div class="col-sm-8">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="vehicle_type_id">{{ Lang::label("Vehicle Type") }} *</label>
                                <div class="form-line  {{ $errors->has('vehicle_type_id') ? 'error focused' : '' }}">
                                    {{ Form::select('vehicle_type_id', $vehicleTypeList,  old('vehicle_type_id'), ['class'=>'form-control bookingPeriod no-select', 'id'=>'vehicle_type_id', 'placeholder'=>Lang::label('Select Option')]) }}
                                </div>
                                @if ($errors->has('vehicle_type_id'))
                                    <label class="error">{{ $errors->first('vehicle_type_id') }}</label>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="vehicle_licence">{{ Lang::label("Vehicle Licence") }} *</label>
                                <div class="form-line  {{ $errors->has('vehicle_licence') ? 'error focused' : '' }}">
                                    {{ Form::select('vehicle_licence', $licenceList,  old('vehicle_licence'), ['class'=>'form-control no-select', 'id'=>'vehicle_licence', 'placeholder'=>Lang::label('Select Option')]) }}
                                </div>
                                @if ($errors->has('vehicle_licence'))
                                    <label class="error">{{ $errors->first('vehicle_licence') }}</label>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="price_id">{{ Lang::label("Period") }} *</label>
                                <div class="form-line  {{ $errors->has('price_id') ? 'error focused' : '' }}">
                                    {{ Form::select('price_id', [],  old('price_id'), ['class'=>'form-control showSchedule no-select', 'id'=>'price_id', 'placeholder'=>Lang::label('Select')]) }}
                                </div>
                                <span></span>
                                @if ($errors->has('price_id'))
                                    <label class="error">{{ $errors->first('price_id') }}</label>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="promocode">{{ Lang::label("Promo code") }}</label>
                                <div class="form-line  {{ $errors->has('promocode') ? 'error focused' : '' }}">
                                    <input type="text" name="promocode" id="promocode" class="form-control" placeholder="{{ Lang::label('Promo code') }}" autocomplete="off" value="{{ old('promocode') }}" />
                                </div>
                                <span class="text-success sr-only"></span>
                                @if ($errors->has('promocode'))
                                    <label class="error">{{ $errors->first('promocode') }}</label>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-group">
                                <label for="arrival_time">{{ Lang::label("Arrival Time") }} *</label>
                                <div class="form-line  {{ $errors->has('arrival_time') ? 'error focused' : '' }}">
                                    <input name="arrival_time" type="text" id="arrival_time" class="form-control datetimepicker showSchedule" placeholder="{{ Lang::label('Arrival Time') }}" value="{{ (old('arrival_time')?old('arrival_time'):date('Y-m-d H:i')) }}"  style="height:27px">
                                </div>
                                @if ($errors->has('arrival_time'))
                                    <label class="error">{{ $errors->first('arrival_time') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="input-group departure_section">
                                <label for="departure_time">{{ Lang::label('Departure Time') }} *</label>
                                <div class="form-line {{ $errors->has('departure_time') ? 'error focused' : '' }}">
                                    <input type="text" id="departure_time" class="form-control" placeholder="{{ Lang::label('Departure Time') }}"  disabled readonly style="height:27px" value="{{ old('departure_time') }}">
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


                <div class="col-sm-4 bg-success p-1">
                    <!-- NET PRICE -->
                    <div class="input-group">
                        <label for="net_price">{{ Lang::label('Net Price') }} </label>
                        <div class="form-line  {{ $errors->has('net_price') ? 'error focused' : '' }}">
                            <input name="net_price" type="text" id="net_price" class="form-control" placeholder="{{ Lang::label('Net Price') }}" value="{{ old('net_price')?old('net_price'):0.0 }}" autocomplete="off" readonly style="height:27px">
                        </div>
                        <span class="text-success sr-only"></span>
                        @if ($errors->has('net_price'))
                            <label class="error">{{ $errors->first('net_price') }}</label>
                        @endif
                    </div>

                    <!-- DISCOUNT -->
                    <div class="input-group">
                        <label for="discount">{{ Lang::label('Discount') }}</label>
                        <div class="form-line  {{ $errors->has('discount') ? 'error focused' : '' }}">
                            <input name="discount" type="text" id="discount" class="form-control" placeholder="{{ Lang::label('Discount') }}" value="{{ old('discount')?old('discount'):0.0 }}" autocomplete="off" style="height:27px" readonly>
                        </div>
                        <span class="text-success sr-only"></span>
                        @if ($errors->has('discount'))
                            <label class="error">{{ $errors->first('discount') }}</label>
                        @endif
                    </div>

                    <!-- VAT -->
                    <div class="input-group">
                        <label for="vat">{{ Lang::label('Vat') }} = {{ (($setting->vat_type==1)?($setting->vat . '%  of ' . Lang::label('Net Price')): ($setting->vat . ' ' . $setting->currency)) }}</label>
                        <div class="form-line">
                            <input name="vat" type="text" id="vat" class="form-control" placeholder="{{ Lang::label('Vat') }}" value="{{ old('vat')?old('vat'):0.0 }}" style="height:27px" readonly>
                        </div>
                    </div>

                    <!-- GRAND TOTAL -->
                    <div class="input-group">
                        <p><strong>{{ Lang::label('Fine') }} = {{ $setting->fine }} {{ (($setting->fine_type==1)?'% of '. Lang::label('Net Price'). ' + '. Lang::label('Extra Time Price'):$setting->currency) }}</strong> (Depend on late departure time & tolerance 30 minutes)</p>

                        <label for="total_price">{{ Lang::label('Total Price') }} </label>
                        <div class="form-line  {{ $errors->has('total_price') ? 'error focused' : '' }}">
                            <input name="total_price" type="text" id="total_price" class="form-control" placeholder="{{ Lang::label('Total Price') }}" value="{{ old('total_price')?old('total_price'):0.0 }}" autocomplete="off" style="height:27px" readonly>
                        </div>
                        <span class="text-success sr-only"></span>
                        @if ($errors->has('total_price'))
                            <label class="error">{{ $errors->first('total_price') }}</label>
                        @endif
                    </div>

                    <div class="input-group">
                        <label for="note">{{ Lang::label('Note') }}</label>
                        <div class="form-line  {{ $errors->has('note') ? 'error focused' : '' }}">
                            <textarea name="note" type="text" id="note" class="form-control" placeholder="Note">{{ old('note') }}</textarea>
                        </div>
                        @if ($errors->has('note'))
                            <label class="error">{{ $errors->first('note') }}</label>
                        @endif
                    </div>

                    <div class="input-group text-right">
                        <input type="reset" class="btn btn-info" value="{{ Lang::label('Reset') }}">

                        @if(!empty($setting->paypal_client_id) || !empty($setting->paypal_secret_key))
                            <button type="submit" class="btn btn-success waves-effect">{{ Lang::label('Pay with PayPal & Booking Now') }}</button>
                        @else
                            <div class="alert alert-warning">Please add PayPal credentials (Client ID & Secret) to booking a space!</div>
                        @endif
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="card mb-2"> 

            <h4 class="header text-uppercase"><i class="material-icons">place</i> {{ Lang::label("Google Map") }}</h4>

            <div class="body">
                <!-- Google Map -->
                <p id="success" class="sr-only alert alert-success"></p>
                <p id="error" class="sr-only alert alert-danger"></p>

                <div class="form-group">
                    <input type="hidden" id="start" value="1">
                    <input type="text" class="form-control" id="pac-input" placeholder="Select your location">
                </div> 

                <div class="form-group">
                    <div id="map" style="width:100%;height:360px"></div>
                </div>
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
    | show prices
    |____________________________________________________________
    |
    */
    $('#priceModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id     = button.data('id');
        var title  = button.data('title');
        var modal = $(this);
        modal.find('.modal-title').text(title);

        $.ajax({
            url: '{{ URL::to("website/home/parking/prices") }}',
            type: 'get',
            dataType: 'json', 
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: { 'place_id': id },
            success: function(data)
            {  
                var body = "<table width='100%' border='1' class='text-center'>"+
                    "<thead><tr>"+
                    "<th class='text-center'>{{ Lang::label('Vehicle Type') }}</th>"+
                    "<th class='text-center'>{{ Lang::label('Time') }}</th>"+
                    "<th class='text-center'>{{ Lang::label('Unit') }}</th>"+
                    "<th class='text-center'>{{ Lang::label('Price') }}</th>"+
                    "</tr></thead><tbody>";

                $.each(data, function(i, v)
                {  
                    body += "<tr>"+
                        "<td>"+v.vehicle_type+"</td>"+
                        "<td>"+v.time+"</td>"+
                        "<td>"+v.unit+"</td>"+
                        "<td>{{ $setting->currency }} "+v.price+"</td>"+
                    "</tr>";
                });

                if (data=='')
                {
                    body += "<tr><td colspan='4'>Price is not set yet!</td></tr>";
                }

                body += "</tbody></table>";

                modal.find('.modal-body').html(body);
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
    | Select parking slot
    |____________________________________________________________
    |
    */
    $('body').on('click', '.res-slot', function()
    { 
        var slot = $(this); 
        var login = '{{ session()->get("isLogin") }}';
        if (!login)
        {
            $("#authModal").modal('show');
        }
        else if (slot.attr('data-item') != "selected") 
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
            url: '{{ url("website/booking/period") }}',
            type    : 'post',
            data    : {
                place_id: $("input[name=place_id]").val(),
                vehicle_type_id: $("select[name=vehicle_type_id]").val()
            },
            success: function(data)
            {
                var options = "<option value=''>Select</option>";
                if (data != '')
                {
                    $.each(data, function(i, v){
                        options += "<option value='"+i+"'>"+v+"</option>";
                    });
                    $('#price_id').html(options).parent().next().text('');
                }
                else 
                {
                    $('#price_id').html(options).parent().next().text('No price & time period set yet!').addClass('text-danger');
                }
            },
            error: function(xhr) 
            {
                console.log("failed");
            }
        });     
    });
    

    /*
    |____________________________________________________________
    |
    | Get schedule and price
    |____________________________________________________________
    |
    */
    $('body').on('change', '.showSchedule', function()
    { 
        var place_id        = $("input[name=place_id]");
        var price_id        = $("select[name=price_id]");
        var arrival_time    = $("input[name=arrival_time]");
        var departure_time  = $("input[name=departure_time]");
        var vehicle_type_id = $("select[name=vehicle_type_id]");
        var net_price       = $("input[name=net_price]");
        var vat             = $("input[name=vat]");

        $("#output").removeClass('sr-only alert-danger alert').html('');
        //reseting previous value
        $("#scheduleSpaces").html("");
        $("#departure_time").val("");

        if(place_id.val() && price_id.val() && vehicle_type_id.val() && arrival_time.val())
        {   
            //ajax call
            $.ajax({
                url: '{{ URL::to("website/booking/show-schedule") }}',
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
                        $("#scheduleSpaces").html(data.spaces);
                        // set form inputs
                        departure_time.val(data.departure_time);
                        net_price.val(data.price);
                        vat.val(data.vat);
                        departure_time.val(data.departure_time);
                        $("#departure_time").val(data.departure_time);
                    } 

                    //calculate total price
                    calculateTotalPrice();
                },
                error: function(xhr)
                {
                    console('failed...');
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
    $('input[name=promocode]').on('keyup', function()
    { 
        $.ajax({
            url: '{{ URL::to("website/booking/promocode") }}',
            type: 'post',
            dataType: 'json', 
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: { 
                'promocode': $(this).val(), 
                'arrival_time': $('input[name=arrival_time]').val() 
            },
            success: function(data)
            {
                if (data.status)
                {
                    $('input[name=promocode_id]').val(data.promocode_id);
                    $('input[name=discount]').val(data.discount);
                    $('input[name=promocode]').parent().next().text(data.success).addClass('text-success').removeClass('text-danger sr-only');
                }
                else 
                {
                    $('input[name=promocode_id]').val("");
                    $('input[name=discount]').val("0");
                    $('input[name=promocode]').parent().next().text(data.exception).addClass('text-danger').removeClass('text-success sr-only');
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
    | Booking Submit
    |____________________________________________________________
    |
    */
    // var bookingFrm = $("#bookingFrm");
    // bookingFrm.on('submit', function(e)
    // {
    //     $("#output").html("");
    //     e.preventDefault(); 
    //     $.ajax({
    //         type: $(this).attr('method'),
    //         url: $(this).attr('action'),
    //         dataType: "JSON",
    //         data: bookingFrm.serialize(),
    //         beforeSend: function() {
    //              $("#bookingMessage").html("<div class=\"preloader\" style=\"padding:10px\">"+
    //                     "<div class=\"spinner-layer pl-indigo\">"+
    //                         "<div class=\"circle-clipper left\">"+
    //                             "<div class=\"circle\"></div>"+
    //                         "</div>"+
    //                         "<div class=\"circle-clipper right\">"+
    //                             "<div class=\"circle\"></div>"+
    //                         "</div>"+
    //                     "</div>"+
    //                 "</div>");  
    //         },
    //         success: function(data)
    //         { 
    //             $("#bookingMessage").html(""); 
    //             console.log(data)

    //             if (data.status)
    //             {
    //                 // var restorepage = $('body').html();
    //                 // $('body').empty().html(data.result);
    //                 // window.print();
    //                 // $('body').html(restorepage); 
    //                 // history.go(0);
    //             }
    //             else
    //             {
    //                 $("#bookingMessage").html("<div class='alert alert-danger alert-dismissible'><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>" + data.exception + "</div>");
    //             }
    //         },
    //         error:function()
    //         {
    //             alert('failed');
    //         }
    //     }); 

    // })
    // .validate({
    //     highlight: function (input) {
    //         $(input).parents('.form-line').addClass('error');
    //     },
    //     unhighlight: function (input) {
    //         $(input).parents('.form-line').removeClass('error');
    //     },
    //     errorPlacement: function (error, element) {
    //         $(element).parents('.form-group').append(error);
    //     }
    // });

}); 

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
        country: 'bd',
        zoom: parseInt("{{ ($setting->map_zoom?$setting->map_zoom:15) }}"),
        lat: parseFloat("{{ ($setting->latitude?$setting->latitude:1) }}"),
        lng: parseFloat("{{ ($setting->longitude?$setting->longitude:1) }}"), 
        title: ("{{ ($setting->title?$setting->title:'Demo Application') }}"),  
        location: { 
            @foreach($placeLocation as $key => $v)
            <?php echo "$key:{title:\"".htmlentities($v->name)."\", lat:'$v->latitude', lng:'$v->longitude'}," ?> 
            @endforeach
        }
    }; 

    function initMap() 
    { 
        map = new google.maps.Map(document.getElementById('map'), {
            zoom  : config.zoom,
            center: {lat: config.lat, lng: config.lng},
            mapTypeControl: true,
            mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
            navigationControl: true,
            navigationControlOptions: {style: google.maps.NavigationControlStyle.ZOOM_PAN},
            mapTypeId: google.maps.MapTypeId.TRANSIT
        }); 

        for (var count in config.location) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(config.location[count].lat, config.location[count].lng),
                map: map,
                title: config.location[count].title
            });


            // add custom label
            var content = config.location[count].title;   
            var infowindow = new google.maps.InfoWindow({
                content: '<strong style="color:#3F51B5;font-weight:bolder">'+content+'</strong>'
            }); 
            infowindow.open(map, marker); 

            //click to show/hide info window
            google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
                return function() {
                    infowindow.setContent(content);
                    infowindow.open(map,marker);
                };
            })(marker,content,infowindow));   
        }


        //------------------------------------------------------------
        // HTML5 geolocation. 
        if (navigator.geolocation) 
        {
            navigator.geolocation.getCurrentPosition(function geolocationSuccess(position) {
                pos = {
                  lat: position.coords.latitude,
                  lng: position.coords.longitude
                };  
                document.getElementById("start").value = position.coords.latitude+', '+position.coords.longitude;
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
                makeNewMarker(pos, 'My Location'); 
            }, function() {
                errorStatus.innerHTML = 'browser doesn\'t support geolocation'; 
                errorStatus.classList.remove("sr-only"); 
            });
        }  
        //------------------------------------------------------------
        /* autocomplete search*/
        var input = document.getElementById('pac-input'); 
        var options = {
            componentRestrictions: {country: config.country}
        };
        var autocomplete = new google.maps.places.Autocomplete(input, options);
        autocomplete.addListener('place_changed', function() {

          var place = autocomplete.getPlace();
          if (place.geometry) 
          {
            var position = place.geometry.location.lat()+", "+place.geometry.location.lng();

            document.getElementById("start").value = position;
            //display location
            makeNewMarker(place.geometry.location, 'My Location');
            //change direction
            onChangeDirectionHandler();

          } else {
            errorStatus.innerHTML = "No details available for input: '" + place.name + "'";
            errorStatus.classList.remove("sr-only");
          }  
        });

        //------------------------------------------------------------
        /* get direction with select / click*/
        var onChangeDirectionHandler = function() {
            var start = document.getElementById('start').value;
            var end   = document.getElementById('place_id').value;
            var title = document.getElementById('place_id');
            var title = title.options[title.selectedIndex].text;
 
            var loc = end.split(','); 
            var end = loc[1]+","+loc[2]; 

            // make new direction 
            makeNewDirection(start, end);
        };
        // document.getElementById('place_id').addEventListener('change', onChangeDirectionHandler);


        var geoLoc = document.getElementsByClassName('showMapByGeoLocation');
        for (var i = 0; i < geoLoc.length; i++) {  
            geoLoc[i].addEventListener('click', function(){
                var start = document.getElementById('start').value;
                var end   = this.getAttribute("data-geolocation");
                var title = this.getAttribute("data-title");

                var loc = end.split(','); 
                var end = loc[1]+","+loc[2]; 
                // make new direction 
                makeNewDirection(start, end);
            });
        }

        //------------------------------------------------------------
        function makeNewDirection(start, end) {
            if(directionsDisplay)
            {
                directionsDisplay.setMap(null);
                //remove previous marker
                lastMarker.setMap(null); 
            }
            makeNewMarker(null, null); 


            directionsDisplay = new google.maps.DirectionsRenderer({
                polylineOptions: {
                  strokeColor: "#3F51B5"
                } 
            });
            directionsDisplay.setMap(map);

            var request = {
                origin      : start,
                destination : end,
                optimizeWaypoints: false,
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
                    errorStatus.classList.add("sr-only"); 
                }
            });
        }


        //------------------------------------------------------------
        function makeNewMarker(position, title)
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
                strokeColor: "#3F51B5"
            }); 
            map.setCenter(position); 

            // add custom label
            var infowindow = new google.maps.InfoWindow({
                content: '<strong style="color:#3F51B5;font-weight:bolder">'+title+'</strong>'
            }); 
            infowindow.open(map, lastMarker); 
        }

                //------------------------------------------------------------
        function myDirection(origin)
        {  
            makeNewMarker(null, null); 

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
    } 
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $setting->map_api_key }}&maptype=roadmap&libraries=places&callback=initMap"></script>
@endsection