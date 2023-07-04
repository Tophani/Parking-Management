@extends('operator.template')
 
@section('content')
<!-- Exportable Table -->
<div class="row clearfix">

    <div class="col-sm-8">
        <div class="card">
            <div class="header">
                <h2>
                    {{ $title }} 
                </h2>
            </div> 

            <div class="body">  
                <dl class="dl-horizontal">
                    <dt>{{ Lang::label('Name') }}</dt><dd>&nbsp;{{ $place->name }}</dd>
                    <dt>{{ Lang::label('Address') }}</dt><dd>&nbsp;{{ $place->address }}</dd>
                    <dt>{{ Lang::label('Latitude') }}</dt><dd>&nbsp;{{ $place->latitude }}</dd>
                    <dt>{{ Lang::label('Longitude') }}</dt><dd>&nbsp;{{ $place->longitude }}</dd>
                    <dt>{{ Lang::label('Limit') }}</dt><dd>&nbsp;{{ $place->limit }}</dd>
                    <dt>{{ Lang::label('Space') }}</dt>
                        <dd>
                            @foreach(explode(',', $place->space) as $serial)
                                <span class="tag label label-info">{{ $serial }}<span data-role="remove"></span></span>
                            @endforeach
                            &nbsp;
                        </dd>
                    <dt>{{ Lang::label('Note') }}</dt><dd>&nbsp;{{ $place->note }}</dd>
                    <dt>{{ Lang::label('Status') }}</dt>
                        <dd class="switch">
                            <label>
                                OFF<input name="status" type="checkbox" {{ (($place->status==1)?'checked':null) }} disabled>
                                <span class="lever"></span>ON
                            </label> 
                        </dd>
                </dl>  
            </div>
        </div> 
    </div>

    <div class="col-sm-4">
        <div class="card">
            <div class="body">
                <div class="form-group">
                    <div id="map" style="width:100%;height:220px"></div>
                </div>  
            </div>
        </div>
    </div>

    <div class="col-sm-12"> 
        <div class="card">
            <div class="header">
                <h2>{{ Lang::label("Prices") }}</h2>
            </div> 

            <div class="body">
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
@endsection

<!-- JavaScript -->
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

    // settings from database   
    var map, marker, infowindow;
    var latitude  = parseFloat("{{ ($setting->latitude?$setting->latitude:1) }}");
    var longitude = parseFloat("{{ ($setting->longitude?$setting->longitude:1) }}");
    
    var marTit = "{{ ($place->name?$place->name:null) }}";
    var marLat = parseFloat("{{ ($place->latitude?$place->latitude:1) }}");
    var marLng = parseFloat("{{ ($place->longitude?$place->longitude:1) }}");

    function initMap() 
    {
        // initial map setting
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 15,
          mapTypeId: 'roadmap',
        });
        map.setCenter({lat: latitude, lng: longitude});
 
        marker = new google.maps.Marker({
            position: {lat: marLat, lng: marLng},
            map: map,
            draggable: false
        });
        marker.setPosition({lat: marLat, lng: marLng});

        infowindow = new google.maps.InfoWindow({
            content: '<strong style="color:green;font-weight:bolder">'+(marTit?marTit:"Parking Place")+'</strong>'
        }); 
        infowindow.open(map, marker); 
    }  
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $setting->map_api_key }}&maptype=roadmap&libraries=places&callback=initMap"></script>
@endsection 

