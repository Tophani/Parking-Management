@extends('website.template')

@section('content')

@if (!empty($setting->slider_1) || !empty($setting->slider_2) || !empty($setting->slider_3))
<div id="slideshow" class="carousel slide mb-2" data-ride="carousel">
    <div class="carousel-inner" role="listbox">
        @if (!empty($setting->slider_1))
        <div class="item active">
            <img src="{{ ($setting->slider_1) }}">
            <div class="carousel-caption"> 
                <p>{{ $setting->slider_1_text }}</p>
            </div>
        </div>
        @endif
        @if (!empty($setting->slider_2))
        <div class="item left">
            <img src="{{ ($setting->slider_2) }}">
            <div class="carousel-caption">
                <p>{{ $setting->slider_2_text }}</p>
            </div>
        </div>
        @endif
        @if (!empty($setting->slider_3))
        <div class="item next left">
            <img src="{{ ($setting->slider_3) }}">
            <div class="carousel-caption">
                <p>{{ $setting->slider_3_text }}</p>
            </div>
        </div>
        @endif
    </div>
    <a class="left carousel-control" href="#slideshow" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#slideshow" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
@endif

<div class="row">
    <!-- PARKING PLACES -->
    <div class="col-sm-12 mb-2">
        <div class="profiles-details">
            @if(!session()->get('isLogin'))
            <div class="alert alert-danger">Please login first to booking a space</div>
            @endif 
            @if(empty($setting->paypal_client_id) || empty($setting->paypal_secret_key))
            <div class="alert alert-warning">Please add PayPal credentials (Client ID & Secret) to booking a space!</div>
            @endif
            <table class="table table-condensed table-bordered table-hover">
                <thead>
                    <tr class="bg-indigo">
                        <th><i class="glyphicon glyphicon-flag"></i> {{ Lang::label("Parking Lots") }}</th>
                        <th class="hidden-xs">{{ Lang::label('Total') }}</th>
                        <th class="hidden-xs">{{ Lang::label('Occupied') }}</th>
                        <th>{{ Lang::label('Available') }}</th>
                        <th class="hidden-xs">{{ Lang::label('Is Price Set?') }}</th>
                        <th><i class="material-icons">settings</i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lots as $lot)
                    <tr>
                        <th data-geolocation="{{ $lot->geolocation }}"  data-title="{{ $lot->name }}" class="showMapByGeoLocation" style="cursor:pointer;word-wrap:break-word;">{{ $lot->name }}</th>
                        <td class="hidden-xs"><strong class="label label-info">{{ $lot->total_space }}</strong></td>
                        <td class="hidden-xs"><strong class="label label-warning">{{ $lot->occupied }}</strong></td>
                        <td><strong class="label label-success">{{ $lot->available }}</strong></td>
                        @if($lot->is_price=='Yes')
                            <td class="hidden-xs"><strong class="label label-success">{{ $lot->is_price }}</strong></td>
                        @else
                            <td class="hidden-xs"><strong class="label label-danger">{{ $lot->is_price }}</strong></td>
                        @endif
                        <td>
                            <button class="btn btn-xs btn-primary" data-id="{{ $lot->place_id }}" data-title="{{ $lot->name }}" data-toggle="modal" data-target="#priceModal"><i class="material-icons">attach_money</i></button>
                            <button class="btn btn-xs btn-info hidden-xs showMapByGeoLocation" data-geolocation="{{ $lot->geolocation }}"><i class="material-icons">place</i></button>
                            @if(session()->get('isLogin') && $lot->is_price=='Yes')
                            <a class="btn btn-sm btn-success" href="{{ url('website/booking?place_id='.$lot->place_id) }}">Booking Now</a>
                            @else
                            <button type="button" class="btn btn-sm btn-success" disabled>Booking Now</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table> 
        </div>
    </div>

    <!-- Google Map -->
    <div class="col-sm-12 mb-2">
        <div class="profiles-details">
            <h4 class="header text-uppercase"><i class="material-icons">place</i> {{ Lang::label("Google Map") }}</h4>
            
            <div class="body">
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

    <!-- ABOUT -->
    <div class="col-sm-12 mb-2">
        <div class="profiles-details">
            <h4 class="header text-uppercase"><i class="glyphicon glyphicon-info-sign"></i> {{ Lang::label("About") }}</h4>
            
            <div class="body">{{ $setting->about }}</div>
        </div>
    </div> 

    <!-- SEND MAIL -->
    <div class="col-sm-12 mb-2">
        <div class="profiles-details">
            <h4 class="header text-uppercase"><i class="glyphicon glyphicon-envelope"></i> {{ Lang::label("Contact Us") }}</h4>
            
            <div class="body">
                <p id="sendMailOutput" class="alert hide"></p>
                {{ Form::open(['url' => 'website/mail/send', 'id'=>'sendMail']) }}
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" name="email" class="form-control">
                        <label class="form-label">{{ Lang::label('Email') }}</label>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <input type="text" name="subject" class="form-control">
                        <label class="form-label">{{ Lang::label('Subject') }}</label>
                    </div>
                </div>
                <div class="form-group form-float">
                    <div class="form-line">
                        <textarea type="text" name="message" class="form-control"></textarea>
                        <label class="form-label">{{ Lang::label('Message') }}</label>
                    </div>
                </div>
                <div class="form-group form-float text-right">
                    <button type="submit" class="btn btn-top waves-effect">{{ Lang::label('Send') }}</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div> 
</div>

<!-- PRICE MODAL -->
<div class="modal fade" id="priceModal" tabindex="-1" role="dialog" aria-labelledby="priceModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="priceModalLabel">{{ Lang::label("Prices") }}</h4>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
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
    | send email 
    |____________________________________________________________
    |
    */
    $("body").on("submit", "#sendMail", function(e){
        e.preventDefault();
        var formData = $(this).serializeArray();

        //ajax call
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            dataType: 'json', 
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: formData,
            success: function(data)
            {   
                if (data.status)
                {
                    $("#sendMailOutput").removeClass("hide alert-danger").addClass("alert-success").html(data.message);
                }
                else
                {
                    $("#sendMailOutput").removeClass("hide alert-success").addClass("alert-danger").html(data.message);
                }
            },
            error: function(xhr)
            {
                alert('failed...');
            }
        });

    }); 
    
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