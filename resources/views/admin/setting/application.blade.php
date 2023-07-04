@extends('admin.template')

@section('content')
<div class="row clearfix">
    <div class="col-sm-6">
        <div class="card">
            <div class="header">
                <h2>{{ $title }}</h2>
                <div class="header-dropdown m-r-0"> 
                    <div class="switch {{ $errors->has('status') ? 'error focused' : '' }}">
                        <label>
                            <strong>{{ Lang::label("Website") }}</strong>&nbsp;
                            Disable <input name="website" type="checkbox" value="{{ ($setting->website_enable?1:0) }}" {{ ($setting->website_enable?'checked':'') }}>
                            <span class="lever"></span> Enable
                        </label>
                        @if ($errors->has('status'))
                            <label class="error">{{ $errors->first('status') }}</label>
                        @endif
                    </div>
                </div>
            </div>

            <div class="body">
                {!! Form::open(['url' => 'admin/setting/app', 'class' => 'form-validation frmValidation', 'files' => true]) !!}

                    {!! Form::hidden('id', $setting->id) !!}
                    
                    <label for="title">{{ Lang::label('Application Title') }} *</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('title') ? 'error focused' : '' }}">
                            <input name="title" type="text" id="title" class="form-control" placeholder="{{ Lang::label('Application Title') }}" value="{{ $setting->title }}">
                        </div>
                        @if ($errors->has('title'))
                            <label class="error">{{ $errors->first('title') }}</label>
                        @endif
                    </div>

                    <label for="about">{{ Lang::label('About') }}</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('about') ? 'error focused' : '' }}">
                            <textarea name="about" type="text" id="about" class="form-control" placeholder="{{ Lang::label('About') }}">{{ $setting->about }}</textarea>
                        </div>
                        @if ($errors->has('about'))
                            <label class="error">{{ $errors->first('about') }}</label>
                        @endif
                    </div>

                    <label for="meta_keyword">{{ Lang::label('Meta Keyword') }}</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('meta_keyword') ? 'error focused' : '' }}">
                            <textarea name="meta_keyword" type="text" id="meta_keyword" class="form-control" placeholder="{{ Lang::label('Meta Keyword') }}">{{ $setting->meta_keyword }}</textarea>
                        </div>
                        @if ($errors->has('meta_keyword'))
                            <label class="error">{{ $errors->first('meta_keyword') }}</label>
                        @endif
                    </div>

                    <label for="meta_description">{{ Lang::label('Meta Description') }}</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('meta_description') ? 'error focused' : '' }}">
                            <textarea name="meta_description" type="text" id="meta_description" class="form-control" placeholder="{{ Lang::label('Meta Description') }}">{{ $setting->meta_description }}</textarea>
                        </div>
                        @if ($errors->has('meta_description'))
                            <label class="error">{{ $errors->first('meta_description') }}</label>
                        @endif
                    </div>

                    <label for="email">{{ Lang::label('Email') }} *</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('email') ? 'error focused' : '' }}">
                            <input name="email" type="text" id="email" class="form-control" placeholder="{{ Lang::label('Email') }}" value="{{ $setting->email }}">
                        </div>
                        @if ($errors->has('email'))
                            <label class="error">{{ $errors->first('email') }}</label>
                        @endif
                    </div>

                    <label for="phone">{{ Lang::label('Phone') }}</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('phone') ? 'error focused' : '' }}">
                            <input name="phone" type="text" id="phone" class="form-control" placeholder="{{ Lang::label('Phone') }}" value="{{ $setting->phone }}">
                        </div>
                        @if ($errors->has('phone'))
                            <label class="error">{{ $errors->first('phone') }}</label>
                        @endif
                    </div>

                    <label for="address">{{ Lang::label('Address') }}</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('address') ? 'error focused' : '' }}">
                            <textarea name="address" type="text" id="address" class="form-control" placeholder="{{ Lang::label('Address') }}">{{ $setting->address }}</textarea>
                        </div>
                        @if ($errors->has('address'))
                            <label class="error">{{ $errors->first('address') }}</label>
                        @endif
                    </div>
  
                    <label for="favicon">{{ Lang::label('Favicon') }}</label>
                    <div class="form-group"> 
                        <div class="form-line {{ $errors->has('favicon') ? 'error focused' : '' }}">
                        <div class="row">
                            <div class="col-sm-3">
                                <img src="{{ asset(old('favicon')?old('favicon'):$setting->favicon) }}" width="32" height="32" class="img-thumbnail">
                            </div>

                            <div class="col-sm-9">
                                <input name="favicon" type="file" id="favicon" class="form-control">
                                <input name="old_favicon" type="hidden" value="{{ $setting->favicon }}">
                            </div>
                        </div>
                        </div>
                        @if ($errors->has('favicon'))
                            <label class="error">{{ $errors->first('favicon') }}</label>
                        @endif
                    </div>
  
                    <label for="logo">{{ Lang::label('Logo') }}</label>
                    <div class="form-group"> 
                        <div class="form-line {{ $errors->has('logo') ? 'error focused' : '' }}">
                        <div class="row">
                            <div class="col-sm-3">
                                <img src="{{ asset(old('logo')?old('logo'):$setting->logo) }}" width="190" height="50" class="img-thumbnail">
                            </div>

                            <div class="col-sm-9">
                                <input name="logo" type="file" id="logo" class="form-control">
                                <input name="old_logo" type="hidden" value="{{ $setting->logo }}">
                            </div>
                        </div>
                        </div>
                        @if ($errors->has('logo'))
                            <label class="error">{{ $errors->first('logo') }}</label>
                        @endif
                    </div>
  
                    <label for="slider_1">{{ Lang::label('Slider 1') }}</label>
                    <div class="form-group"> 
                        <div class="form-line {{ $errors->has('slider_1') ? 'error focused' : '' }}">
                        <div class="row">
                            <div class="col-sm-3">
                                <img src="{{ asset(old('slider_1')?old('slider_1'):$setting->slider_1) }}" width="190" height="50" class="img-thumbnail">
                            </div>

                            <div class="col-sm-9">
                                <input name="slider_1" type="file" id="slider_1" class="form-control">
                                <input name="old_slider_1" type="hidden" value="{{ $setting->slider_1 }}">
                                <button type="button" class="removeSlide btn btn-xs btn-danger" onclick="return confirm('Are you sure')">Remove</button>
                                <div class="form-line">
                                    <textarea name="slider_1_text" type="text" id="slider_1_text" class="form-control" placeholder="{{ Lang::label('Write someting') }}">{{ $setting->slider_1_text }}</textarea>
                                </div>
                            </div>
                        </div>
                        </div>
                        @if ($errors->has('slider_1'))
                            <label class="error">{{ $errors->first('slider_1') }}</label>
                        @endif
                    </div>
  
                    <label for="slider_2">{{ Lang::label('Slider 2') }}</label>
                    <div class="form-group"> 
                        <div class="form-line {{ $errors->has('slider_2') ? 'error focused' : '' }}">
                        <div class="row">
                            <div class="col-sm-3">
                                <img src="{{ asset(old('slider_2')?old('slider_2'):$setting->slider_2) }}" width="290" height="50" class="img-thumbnail">
                            </div>

                            <div class="col-sm-9">
                                <input name="slider_2" type="file" id="slider_2" class="form-control">
                                <input name="old_slider_2" type="hidden" value="{{ $setting->slider_2 }}">
                                <button type="button" class="removeSlide btn btn-xs btn-danger" onclick="return confirm('Are you sure')">Remove</button>
                                <div class="form-line">
                                    <textarea name="slider_2_text" type="text" id="slider_2_text" class="form-control" placeholder="{{ Lang::label('Write someting') }}">{{ $setting->slider_2_text }}</textarea>
                                </div>
                            </div>
                        </div>
                        </div>
                        @if ($errors->has('slider_2'))
                            <label class="error">{{ $errors->first('slider_2') }}</label>
                        @endif
                    </div>
  
                    <label for="slider_3">{{ Lang::label('Slider 3') }}</label>
                    <div class="form-group"> 
                        <div class="form-line {{ $errors->has('slider_3') ? 'error focused' : '' }}">
                        <div class="row">
                            <div class="col-sm-3">
                                <img src="{{ asset(old('slider_3')?old('slider_3'):$setting->slider_3) }}" width="390" height="50" class="img-thumbnail">
                            </div>
                            <div class="col-sm-9">
                                <input name="slider_3" type="file" id="slider_3" class="form-control">
                                <input name="old_slider_3" type="hidden" value="{{ $setting->slider_3 }}">
                                <button type="button" class="removeSlide btn btn-xs btn-danger" onclick="return confirm('Are you sure')">Remove</button>
                                <div class="form-line">
                                    <textarea name="slider_3_text" type="text" id="slider_3_text" class="form-control" placeholder="{{ Lang::label('Write someting') }}">{{ $setting->slider_3_text }}</textarea>
                                </div>
                            </div>
                        </div>
                        </div>
                        @if ($errors->has('slider_3'))
                            <label class="error">{{ $errors->first('slider_3') }}</label>
                        @endif
                    </div>


                    <label for="facebook">{{ Lang::label('Facebook Url') }}</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('facebook') ? 'error focused' : '' }}">
                            <input name="facebook" type="text" id="facebook" class="form-control" placeholder="{{ Lang::label('Facebook Url') }}" value="{{ $setting->facebook }}">
                        </div>
                        @if ($errors->has('facebook'))
                            <label class="error">{{ $errors->first('facebook') }}</label>
                        @endif
                    </div>

                    <label for="twitter">{{ Lang::label('Twitter Url') }}</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('twitter') ? 'error focused' : '' }}">
                            <input name="twitter" type="text" id="twitter" class="form-control" placeholder="{{ Lang::label('Twitter Url') }}" value="{{ $setting->twitter }}">
                        </div>
                        @if ($errors->has('twitter'))
                            <label class="error">{{ $errors->first('twitter') }}</label>
                        @endif
                    </div>

                    <label for="youtube">{{ Lang::label('Youtube Url') }}</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('youtube') ? 'error focused' : '' }}">
                            <input name="youtube" type="text" id="youtube" class="form-control" placeholder="{{ Lang::label('Youtube Url') }}" value="{{ $setting->youtube }}">
                        </div>
                        @if ($errors->has('youtube'))
                            <label class="error">{{ $errors->first('youtube') }}</label>
                        @endif
                    </div>
 
                    <label for="footer">{{ Lang::label('Footer Text') }}</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('footer') ? 'error focused' : '' }}">
                            <textarea name="footer" type="text" id="footer" class="form-control" placeholder="{{ Lang::label('Footer Text') }}">{{ $setting->footer }}</textarea>
                        </div>
                        @if ($errors->has('footer'))
                            <label class="error">{{ $errors->first('footer') }}</label>
                        @endif
                    </div>
 
                    <button type="reset" class="btn btn-danger waves-effect">{{ Lang::label('Reset') }}</button>
                    <button type="submit" class="btn btn-success waves-effect">{{ Lang::label('Update') }}</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <!-- Google map -->
    <div class="col-sm-6">
        <div class="card">
            <div class="header">
                <h2>{{ Lang::label('Google Map') }} <small>Click on the map to set center point. (<span id="error" class="text-danger">Location track automatically.</span>)</small></h2>
            </div>
            <div class="body">
                {!! Form::open(['url' => 'admin/setting/map', 'class' => 'form-validation frmValidation', 'files' => true]) !!}
                    {!! Form::hidden('id', $setting->id) !!} 
 
                    <label for="map_api_key">{{ Lang::label('Google Map Api Key') }} *</label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('map_api_key') ? 'error focused' : '' }}">
                            <input name="map_api_key" type="text" id="map_api_key" class="form-control" placeholder="{{ Lang::label('Google Map Api Key') }}" value="{{ $setting->map_api_key }}">
                        </div>
                        @if ($errors->has('map_api_key'))
                            <label class="error">{{ $errors->first('map_api_key') }}</label>
                        @endif
                    </div>

                    <label for="map_zoom">{{ Lang::label('Map Zoom Level') }} * <small class="text-success">(Range 1 to 20 and the best zoom level is 7)</small></label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('map_zoom') ? 'error focused' : '' }}">
                            <input name="map_zoom" type="text" id="map_zoom" class="form-control" placeholder="{{ Lang::label('Map Zoom Level') }}" value="{{ $setting->map_zoom }}">
                        </div>
                        @if ($errors->has('map_zoom'))
                            <label class="error">{{ $errors->first('map_zoom') }}</label>
                        @endif
                    </div>

                    <label for="lngLat">{{ Lang::label('Latitude & Longitude') }} *</label>
                    <div id="lngLat" class="form-group">
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="form-line  {{ $errors->has('latitude') ? 'error focused' : '' }}">
                                    <input name="latitude" type="text" id="latitude" class="form-control" placeholder="{{ Lang::label('Latitude') }}" value="{{ $setting->latitude }}">
                                </div>
                                @if ($errors->has('latitude'))
                                    <label class="error">{{ $errors->first('latitude') }}</label>
                                @endif
                            </div>
                            <div class="col-sm-5">
                                <div class="form-line  {{ $errors->has('longitude') ? 'error focused' : '' }}">
                                    <input name="longitude" type="text" id="longitude" class="form-control" placeholder="{{ Lang::label('Longitude') }}" value="{{ $setting->longitude }}">
                                </div>
                                @if ($errors->has('longitude'))
                                    <label class="error">{{ $errors->first('longitude') }}</label>
                                @endif
                            </div>
                            <div class="col-sm-2">
                                <button type="button" onclick="placeMarker()" class="btn btn-primary">+</button>
                            </div>
                        </div>  
                    </div>

                    <label for="address">{{ Lang::label('Map Preview') }}</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" id="pac-input" placeholder="Search your location">
                        </div>
                        <div id="map" style="width:100%;height:378px"></div>
                    </div>
                    <button type="reset" class="btn btn-danger waves-effect">{{ Lang::label('Reset') }}</button>
                    <button type="submit" class="btn btn-success waves-effect">{{ Lang::label('Update') }}</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>    

    <!-- Cron Job Setting -->
    <div class="col-sm-6">
        <div class="card">
            <div class="header">
                <h2>{{ Lang::label('Cron Job Setting') }}</h2>
            </div>
            <div class="body"> 
                You only need to add the following Cron entry to your server. 
                <pre class="text-success">* * * * * php <strong class="text-danger" title="Actual Path of Artisan file">/path-to-your-project/artisan</strong> schedule:run >> /dev/null 2>&1</pre> 
            </div>
        </div>
    </div>

    <!-- Paypal credential -->
    <div class="col-sm-6">
        <div class="card">
            <div class="header">
                <h2>{{ Lang::label('PayPal Setting') }}</h2>
            </div>
            <div class="body"> 
                {!! Form::open(['url' => 'admin/setting/paypal', 'class' => 'form-validation']) !!}
                    {!! Form::hidden('id', $setting->id) !!}  
                    <label for="paypal_client_id">{{ Lang::label('Client ID') }} </label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('paypal_client_id') ? 'error focused' : '' }}">
                            <input name="paypal_client_id" type="text" id="paypal_client_id" class="form-control" placeholder="{{ Lang::label('Client ID') }}" value="{{ $setting->paypal_client_id }}">
                        </div>
                        @if ($errors->has('paypal_client_id'))
                            <label class="error">{{ $errors->first('paypal_client_id') }}</label>
                        @endif
                    </div>

                    <label for="paypal_secret_key">{{ Lang::label('Secret Key') }} </label>
                    <div class="form-group">
                        <div class="form-line  {{ $errors->has('paypal_secret_key') ? 'error focused' : '' }}">
                            <input name="paypal_secret_key" type="text" id="paypal_secret_key" class="form-control" placeholder="{{ Lang::label('Secret Key') }}" value="{{ $setting->paypal_secret_key }}">
                        </div>
                        @if ($errors->has('paypal_secret_key'))
                            <label class="error">{{ $errors->first('paypal_secret_key') }}</label>
                        @endif
                    </div>

                    <div>
                        <button type="reset" class="btn btn-danger waves-effect">{{ Lang::label('Reset') }}</button>
                        <button type="submit" class="btn btn-success waves-effect">{{ Lang::label('Update') }}</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <!-- Notification Setting -->
    <div class="col-sm-6">
        <div class="card">
            <div class="header">
                <h2>{{ Lang::label('Notification Setting') }}</h2>
            </div>
            <div class="body">
                {!! Form::open(['url' => 'admin/setting/notification', 'class' => 'form-validation']) !!}
                    {!! Form::hidden('id', $setting->id) !!} 

                    <div>
                        <div class="demo-checkbox">
                            <input name="sms_notification" type="checkbox" id="sms_notification" value="1" {{ (!empty($setting->sms_notification)?"checked":"") }}>
                            <label for="sms_notification">{{ Lang::label("SMS Notification") }}</label> 
                        </div>
                        @if ($errors->has('sms_notification'))
                            <label class="error">{{ $errors->first('sms_notification') }}</label>
                        @endif
                    </div>

                    <div>
                        <div class="demo-checkbox">
                            <input name="email_notification" type="checkbox" id="email_notification" value="1" {{ (!empty($setting->email_notification)?"checked":"") }}>
                            <label for="email_notification">{{ Lang::label("Email Notification") }}</label> 
                        </div>
                        @if ($errors->has('email_notification'))
                            <label class="error">{{ $errors->first('email_notification') }}</label>
                        @endif
                    </div>
 
                    <div class="input-group">
                    <span class="input-group-addon">{{ Lang::label('SMS Alert') }}</span>
                    <div class="form-line  {{ $errors->has('sms_alert') ? 'error focused' : '' }}">
                        <input name="sms_alert" type="text" id="sms_alert" class="form-control" placeholder="{{ Lang::label('Minutes') }}" value="{{ $setting->sms_alert }}">
                    </div>
                    <span class="input-group-addon">{{ Lang::label('Minutes') }}</span>
                    </div>
                    <span class="help-block text-danger">N.b: Set 0 to stop sms alert</span>
                    @if ($errors->has('sms_alert'))
                        <label class="error">{{ $errors->first('sms_alert') }}</label>
                    @endif

                    <div>
                        <button type="reset" class="btn btn-danger waves-effect">{{ Lang::label('Reset') }}</button>
                        <button type="submit" class="btn btn-success waves-effect">{{ Lang::label('Update') }}</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <!-- Price Setting -->
    <div class="col-sm-6">
        <div class="card">
            <div class="header">
                <h2>{{ Lang::label('Price Setting') }}</h2>
            </div>
            <div class="body">
                {!! Form::open(['url' => 'admin/setting/price', 'class' => 'form-validation']) !!}
                    {!! Form::hidden('id', $setting->id) !!} 
                    <span class="help-block text-danger">N.b: Set value 0 to disabled fine or vat</span>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="input-group">
                                <span class="input-group-addon">{{ Lang::label("Currency") }}</span>
                                <div class="form-line  {{ $errors->has('currency') ? 'error focused' : '' }}">
                                    {{ Form::select('currency', $currency, $setting->currency, ['class'=>'form-control']) }}
                                </div>
                                </div>
                                @if ($errors->has('currency'))
                                    <label class="error">{{ $errors->first('currency') }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="input-group">
                            <span class="input-group-addon">{{ Lang::label('Fine') }}</span>
                            <div class="form-line  {{ $errors->has('fine') ? 'error focused' : '' }}">
                                <input name="fine" type="text" id="fine" class="form-control" placeholder="{{ Lang::label('Amount') }}" value="{{ $setting->fine }}">
                            </div>
                            </div>
                            @if ($errors->has('fine'))
                                <label class="error">{{ $errors->first('fine') }}</label>
                            @endif
                        </div>  

                        <div class="col-sm-4">
                            <div class="input-group">
                            <span class="input-group-addon">{{ Lang::label('Fine Type') }}</span>
                            <div class="form-line  {{ $errors->has('fine_type') ? 'error focused' : '' }}">
                                {{ Form::select('fine_type', ['1'=>Lang::label("Percent"), '0'=>Lang::label("Fixed")], $setting->fine_type, ['class'=>'form-control' ]) }}
                            </div>
                            </div>
                            @if ($errors->has('fine_type'))
                                <label class="error">{{ $errors->first('fine_type') }}</label>
                            @endif
                        </div> 
                    </div> 
                    
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="input-group">
                            <span class="input-group-addon">{{ Lang::label('Vat') }}</span>
                            <div class="form-line  {{ $errors->has('vat') ? 'error focused' : '' }}">
                                <input name="vat" type="text" id="vat" class="form-control" placeholder="{{ Lang::label('Vat') }}" value="{{ $setting->vat }}">
                            </div>
                            </div>
                            @if ($errors->has('vat'))
                                <label class="error">{{ $errors->first('vat') }}</label>
                            @endif
                        </div> 


                        <div class="col-sm-4">
                            <div class="input-group">
                            <span class="input-group-addon">{{ Lang::label('Vat Type') }}</span>
                            <div class="form-line  {{ $errors->has('vat_type') ? 'error focused' : '' }}">
                                {{ Form::select('vat_type', ['1'=>Lang::label("Percent"), '0'=>Lang::label("Fixed")], $setting->vat_type, ['class'=>'form-control' ]) }}
                            </div>
                            </div>
                            @if ($errors->has('vat_type'))
                                <label class="error">{{ $errors->first('vat_type') }}</label>
                            @endif
                        </div> 


                        <div class="col-sm-4 text-right">
                            <button type="reset" class="btn btn-danger waves-effect">{{ Lang::label('Reset') }}</button>
                            <button type="submit" class="btn btn-success waves-effect">{{ Lang::label('Update') }}</button>
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

    //website
    var website = $("input[name=website]");
    website.on("click", function(){
        $.ajax({
            url: '{{ url("admin/setting/website") }}',
            type: 'post',
            dataType: 'json',
            data:{
                _token: $('meta[name="csrf-token"]').attr('content'),
                id : $('input[name=id]').val(),
                website: $(this).val()
            },
            success:function(data)
            {
                console.log(data)
            },
            error: function(xhr)
            {
                alert(xhr.status+" "+xhr.statusText);
            }
        });
    });


    // remove slide images
    $("body").on("click", ".removeSlide", function(){
        $(this).parent().parent().find('img').attr("src", '');
    });



    // settings from database   
    var map, marker;
    var latitude  = parseFloat("{{ ($setting->latitude?$setting->latitude:1) }}");
    var longitude = parseFloat("{{ ($setting->longitude?$setting->longitude:1) }}");
    var zoom      = parseInt("{{ ($setting->map_zoom?$setting->map_zoom:7) }}");

    function initMap() 
    {
        // initial map setting
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: zoom,
          mapTypeId: 'roadmap',
        });

        map.setCenter({lat: latitude, lng: longitude});
        marker = new google.maps.Marker({
            position: {lat: latitude, lng: longitude},
            map: map,
            draggable: false
        });
        marker.setPosition({lat: latitude, lng: longitude});

        // find geoLocation
        if (!latitude || !longitude)
        if (navigator.geolocation) 
        {
            navigator.geolocation.getCurrentPosition(function geolocationSuccess(position) { 
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;

                placeMarker({
                    lat: position.coords.latitude, 
                    lng: position.coords.longitude
                });
                map.setCenter({
                    lat: position.coords.latitude, 
                    lng: position.coords.longitude
                });
                document.getElementById('latitude').value  = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;

            }, function() {
                document.getElementById('error').innerHTML = 'browser doesn\'t support geolocation'; 
            });
        }  

        //autocomplete
        var input = document.getElementById('pac-input'); 
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.addListener('place_changed', function() {

          var place = autocomplete.getPlace();
          if (place.geometry) 
          {
            placeMarker({
                lat: place.geometry.location.lat(), 
                lng: place.geometry.location.lng()
            });
            map.setCenter({
                lat: place.geometry.location.lat(), 
                lng: place.geometry.location.lng()
            }); 
            document.getElementById('latitude').value = place.geometry.location.lat();
            document.getElementById('longitude').value = place.geometry.location.lng();
          } else {
              document.getElementById('error').innerHTML = 'browser doesn\'t support geolocation'; 
          }  
        }); 
        
 
        // add marker
        google.maps.event.addListener(map, 'click', function(event) {
            placeMarker(event.latLng);
            document.getElementById('latitude').value = event.latLng.lat();
            document.getElementById('longitude').value = event.latLng.lng();
        }); 
    } 

    // add a marker
    function placeMarker(location = null) {

        if (!location) 
        { 
            location = {
                lat: parseFloat(document.getElementById('latitude').value),
                lng: parseFloat(document.getElementById('longitude').value),
            }
        } 

        if (marker) { 
            marker.setPosition(location);
        } else {
            marker = new google.maps.Marker({
                position: location,
                map: map,
                draggable: false
            });
        } 
        marker.setMap(map);     
    } 
 
    // chnage marker by latitude
    document.getElementById("latitude").addEventListener('keyup', function() {
        placeMarker({
            lat: parseFloat(document.getElementById('latitude').value),
            lng: parseFloat(document.getElementById('longitude').value),
        });
    });

    // chnage marker by longitude
    document.getElementById("longitude").addEventListener('keyup', function() {
        placeMarker({
            lat: parseFloat(document.getElementById('latitude').value),
            lng: parseFloat(document.getElementById('longitude').value),
        });
    });
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $setting->map_api_key }}&maptype=roadmap&libraries=places&callback=initMap"></script>


@endsection
