@extends('admin.template')
 
@section('content')
<!-- Exportable Table -->
<div class="row clearfix">
    <div class="col-sm-6">
        <div class="card"> 
            <div class="header">
                <h2>{{ $title }}</h2>
                <ul class="header-dropdown m-r--5"> 
                    <li>
                        <a href="{{ url('admin/place/edit/'.$place->id) }}" class="btn btn-sm btn-warning waves-effect">
                            <i class="material-icons">edit</i>
                            <span>{{ Lang::label('Edit Parking Zone') }}</span>
                        </a>
                    </li>  
                    <li>
                        <a href="{{ url('admin/place/new') }}" class="btn btn-sm btn-success waves-effect">
                            <i class="material-icons">add</i>
                            <span>{{ Lang::label('New Parking Zone') }}</span>
                        </a>
                    </li>  
                    <li>
                        <a href="{{ url('admin/place/list') }}" class="btn btn-sm btn-primary waves-effect">
                            <i class="material-icons">list</i>
                            <span>{{ Lang::label('Parking Zones') }}</span>
                        </a> 
                    </li>  
                </ul>
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

    <div class="col-sm-6">
        <div class="card">
            <div class="header">
                <h2>{{ Lang::label('Map Preview') }}</h2>
            </div> 

            <div class="body">
                <div class="form-group">
                    <div id="map" style="width:100%;height:400px"></div>
                </div>  
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- JavaScript -->
<script type="text/javascript">
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

