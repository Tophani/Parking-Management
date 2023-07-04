<?php $app = DB::table('setting')->first(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>{{ (!empty($app->title)?$app->title:null) }}</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon-->
    <link rel="icon" href="{{ asset(!empty($app->favicon)?$app->favicon:'public/assets/images/icons/favicon.ico') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{{ asset('public/assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{ asset('public/assets/css/waves.min.css') }}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{{ asset('public/assets/css/animate.min.css') }}" rel="stylesheet" />

    <!-- Select2 Css -->
    <link href="{{ asset('public/assets/css/select2.min.css') }}" rel="stylesheet" />

    <!-- DateTimePicker Css -->
    <link href="{{ asset('public/assets/css/jquery.datetimepicker.min.css') }}" rel="stylesheet"/>

    <!-- JQuery DataTable Css -->
    <link href="{{ asset('public/assets/css/dataTables.min.css') }}" rel="stylesheet">

    <!-- ADMIN Css -->
    <link href="{{ asset('public/assets/css/admin.bsb.min.css') }}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{ asset('public/assets/css/themes/all-themes.css') }}" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="{{ asset('public/assets/css/website.css') }}" rel="stylesheet">


    <!-- Jquery Core Js -->
    <script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>

</head>
<body class="theme-indigo">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-indigo">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader --> 
 
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a> 
                <a href="{{ url('/') }}">
                     <img class="logo" alt="Brand" src="{{ asset(!empty($app->logo)?$app->logo:'public/assets/images/icons/logo.png') }}">
                </a>
                <a class="navbar-brand hidden-xs" href="{{ url('/') }}">
                    {{ (!empty($app->title)?$app->title:null) }}
                </a>
                
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">

                    <li><a href="{{ url('/') }}" > <span class="glyphicon glyphicon-home"></span> <span>{{ Lang::label('Home') }}</span></a></li>

                    @if(!session()->get('isLogin'))
                    <li><a href="#" class="register" data-toggle="modal" data-target="#authModal"> <span class="glyphicon glyphicon-pencil"></span> <span>{{ Lang::label('Register') }}</span></a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-lock"></span> {{ Lang::label('Login') }} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="auth">
                                <a href="#" data-toggle="modal" data-target="#authModal"><span class="glyphicon glyphicon-lock"></span> {{ Lang::label('User') }}</a>
                                <a href="{{ url('login') }}" target="_blank"><span class="glyphicon glyphicon-lock"></span> {{ Lang::label('Admin') }}</a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    @if(session()->get('isLogin'))
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user"></span>  Auth <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('website/profile') }}">{{ Lang::label('Profile') }}</a></li>
                            <li><a href="{{ url('website/history') }}">{{ Lang::label('Booking History') }}</a></li>
                            <li><a href="{{ url('website/auth/logout') }}"> {{ Lang::label('Logout') }}</a></li>
                        </ul>
                    </li>
                    @endif

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-globe"></span>  {{ ((session()->get('language'))?(ucfirst(session()->get('language')=='default'?'English':session()->get('language'))):'English') }}</a>
                        <ul class="dropdown-menu">
                            @foreach(Lang::languageList() as $lang)
                            <li><a href="{{ url('website/language/'.$lang) }}">{{ ucfirst($lang=='default'?'English':$lang) }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    @if(app()->environment() == 'demo')
                    <li><a href="https://1.envato.market/ck-parking" class="btn p-3 btn-success btn-lg"> BUY NOW!</a></li>
                    @endif
                </ul> 
            </div>
        </div> 
    </nav>
    <!-- #Top Bar --> 

    <section class="content">
        <div class="container">
            @include('alert::alert')
            @yield('content')
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-sm-12 faq mb-2">
                    <div class="col-xs-6 text-left">
                        <b>{{ Lang::label('Email') }}: </b> {{ (!empty($app->email)?$app->email:null) }}<br>
                        <b>{{ Lang::label('Phone') }}: </b> {{ (!empty($app->phone)?$app->phone:null) }}<br>
                    </div>

                    <div class="col-xs-6 text-right">
                        @if (!empty($app->facebook))
                        <a href="{{ ($app->facebook) }}" class="btn btn-primary waves-effect">
                            Facebook
                        </a>
                        @endif
                        @if (!empty($app->twitter))
                        <a href="{{ ($app->twitter) }}" class="btn btn-info waves-effect">
                            Twitter
                        </a>
                        @endif
                        @if (!empty($app->youtube))
                        <a href="{{ ($app->youtube) }}" class="btn btn-danger waves-effect">
                            Youtube
                        </a>
                        @endif
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="copyright">
                        <span class="pull-left">Powered by <a href="http://codekernel.net">CodeKernel</a>. All rights reserved. <span class="label label-primary">v4.0.0</span></span>
                        <span class="pull-right">{{ (!empty($app->footer)?$app->footer:null) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
 
    <!-- LOGIN MODAL --> 
    <div class="modal fade" id="authModal" tabindex="-1" role="dialog" aria-labelledby="authModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <span class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></span>
                <ul class="nav nav-tabs" role="tablist" id="authTab">
                    <li role="presentation" class="active"><a href="#login" aria-controls="login" role="tab" data-toggle="tab">Login</a></li>
                    <li role="presentation"><a href="#register" aria-controls="register" role="tab" data-toggle="tab">Register</a></li>
                </ul>

                <div class="tab-content">
                    <div id="authMessage"></div>
                    <!-- LOGIN -->
                    <div role="tabpanel" class="tab-pane active" id="login">
                        {{ Form::open(array('url' => 'website/auth/login', 'id'=>'loginFrm')) }}
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" name="email" class="form-control">
                                    <label class="form-label">Email Address</label>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="password" name="password" class="form-control">
                                    <label class="form-label">Password</label>
                                </div>
                            </div>

                            <br>
                            <div class="form-group text-right">
                                <button type="submit" class="btn bg-indigo waves-effect waves-effect">LOGIN</button>
                            </div>
                        {{ Form::close() }}

                        @if (app()->environment() == 'demo')
                        <table style="cursor:pointer;font-size:12px" class="autoLogin table table-bordered">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Password</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>client@codekernel.net</td>
                                    <td>12345</td>
                                </tr>
                            </tbody>
                        </table>
                        @endif

                    </div>


                    <!-- REGISTER -->
                    <div role="tabpanel" class="tab-pane" id="register">
                        {{ Form::open(array('url' => 'website/auth/register', 'id'=>'registerFrm')) }}
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" name="name" class="form-control">
                                    <label class="form-label">Name</label>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" name="email" class="form-control">
                                    <label class="form-label">Email Address</label>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" name="mobile" class="form-control">
                                    <label class="form-label">Mobile</label>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" name="licence" class="form-control">
                                    <label class="form-label">Vehicle Licence</label>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="password" name="password" class="form-control">
                                    <label class="form-label">Password</label>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="password" name="confirm_password" class="form-control">
                                    <label class="form-label">Confirm Password</label>
                                </div>
                            </div>
                            <br>
                            <div class="form-group text-right">
                                <button type="submit" class="btn bg-indigo waves-effect waves-effect">REGISTER</button>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>


    <!-- Jquery Core Js -->
    <script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
    <!-- Bootstrap Core Js -->
    <script src="{{ asset('public/assets/js/bootstrap.min.js') }}"></script> 
    <!-- Slimscroll Plugin Js -->
    <script src="{{ asset('public/assets/js/jquery.slimscroll.js') }}"></script>
    <!-- Waves Effect Plugin Js -->
    <script src="{{ asset('public/assets/js/waves.min.js') }}"></script>
    <!--Select2 Js -->
    <script src="{{ asset('public/assets/js/select2.min.js') }}"></script>
    <!-- Datetimepicker Js -->
    <script src="{{ asset('public/assets/js/jquery.datetimepicker.full.min.js') }}"></script>
    <!-- validate Js -->
    <script src="{{ asset('public/assets/js/jquery.validate.min.js') }}"></script>
    <!-- DataTable Js -->
    <script src="{{ asset('public/assets/js/dataTables.min.js') }}"></script> 
    <!-- Custom Js -->
    <script src="{{ asset('public/assets/js/admin.bsb.js') }}"></script>
    @if (app()->environment() == 'demo')  
        <script type="text/javascript">
        if(window.self !== window.top | window.location !== window.parent.location) { 
            var info = document.createElement("h4");
            info.style.background = "wheat";
            info.style.color   = "red";
            info.style.padding = "10px";
            info.style.textAlign = "center";
            info.innerHTML = "Demo is not working? browse directly <a href=\"https://parking.codekernel.net\" target=\"_blank\">CK - Smart Parking Reservation System</a>";     
            document.querySelector('.navbar').appendChild(info);
        }
        </script> 
    @endif
    <script>
    $(document).ready(function() {
        $('table.autoLogin tbody tr').on('click', function() {
            $("#loginFrm input[name=email]").val($(this).children().first().text());
            $("#loginFrm input[name=password]").val($(this).children().first().next().text());
        }); 


        //back to top
        $('body').append('<div id="toTop" class="btn btn-top"><span class="glyphicon glyphicon-chevron-up"></span></div>');
        $(window).scroll(function () {
            if ($(this).scrollTop() !== 0) {
                $('#toTop').fadeIn();
            } else {
                $('#toTop').fadeOut();
            }
        });
        $('#toTop').on('click',function () {
            $("html, body").animate({scrollTop: 0}, 600);
            return false;
        }); 

        // selected register tab
        $('body').on('click', '.register', function(e){
            e.preventDefault();
            $('#authTab a:last').tab('show')
        });
 
        // carousel
        $('.carousel').carousel({
            interval: 2000
        });
     
        //Datetimepicker plugin
        $("body").on("click", ".datetimepicker", function(){
            $('.datetimepicker').datetimepicker({
                format:'Y-m-d H:i', 
                minDate:0,
                minDate: 0,
                step: 5,
            });
        });
            
        //Dropdown          
        // $('.dropdown').hover(function () {
        //     $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(600);
        // }, function () {
        //     $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(600);
        // });

        $("body").on("submit", "#registerFrm",function(e){
            e.preventDefault();
 
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                dataType: 'json',
                data: $(this).serializeArray(),
                beforeSend: function() {
                    $("#authMessage").html("<div class=\"preloader\" style=\"padding:10px\">"+
                        "<div class=\"spinner-layer pl-indigo\">"+
                            "<div class=\"circle-clipper left\">"+
                                "<div class=\"circle\"></div>"+
                            "</div>"+
                            "<div class=\"circle-clipper right\">"+
                                "<div class=\"circle\"></div>"+
                            "</div>"+
                        "</div>"+
                    "</div>"); 
                },
                success: function(data) {
                    if (data.status) {
                        $("#authMessage").html("<div class='alert alert-success'>"+data.message+"</div>");
                    } else {
                        $("#authMessage").html("<div class='alert alert-danger'>"+data.message+"</div>");
                    }
                },
                error: function(xhr) {
                    console.log('error', xhr)
                }
            });
        });

        $("body").on("submit", "#loginFrm",function(e){
            e.preventDefault();
 
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                dataType: 'json',
                data: $(this).serializeArray(),
                beforeSend: function() {
                    $("#authMessage").html("<div class=\"preloader\" style=\"padding:10px\">"+
                        "<div class=\"spinner-layer pl-indigo\">"+
                            "<div class=\"circle-clipper left\">"+
                                "<div class=\"circle\"></div>"+
                            "</div>"+
                            "<div class=\"circle-clipper right\">"+
                                "<div class=\"circle\"></div>"+
                            "</div>"+
                        "</div>"+
                    "</div>"); 
                },
                success: function(data) {
                    $("#authMessage").html("hello world");
                    if (data.status) {
                        $("#authMessage").html("<div class='alert alert-success'>"+data.message+"</div>");
                        setInterval(function(){ location.reload(); }, 1500)
                    } else {
                        $("#authMessage").html("<div class='alert alert-danger'>"+data.message+"</div>");
                    }
                },
                error: function(xhr) {
                    console.log('error', xhr)
                }
            });
        });
        
    });

    //print a div
    function printContent(el){
        var restorepage  = $('body').html();
        var printcontent = $('#' + el).clone();
        $('body').empty().html(printcontent);
        window.print();
        $('body').html(restorepage); 
        history.go(0);
    } 
    </script>
    @yield('scripts')
    @yield('alert')
</body>
</html>
