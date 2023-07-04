<?php $app = DB::table('setting')->first(); ?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Title -->
    <title>{{ (!empty($app->title)?$app->title:null) }} :: {{ (isset($title)?$title:null) }}</title>
    <!-- Favicon-->
    <link rel="icon" href="{{ asset(!empty($app->favicon)?$app->favicon:'public/assets/images/icons/favicon.ico') }}" type="image/x-icon">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400&subset=latin" rel="stylesheet">
    <!-- Bootstrap Core Css -->
    <link href="{{ asset('public/assets/css/bootstrap.min.css') }}" rel="stylesheet" media="all">    
    <!-- Select2 Css -->
    <link href="{{ asset('public/assets/css/select2.min.css') }}" rel="stylesheet"> 
    <!-- Tags Input Css -->
    <link href="{{ asset('public/assets/css/bootstrap-tagsinput.css') }}" rel="stylesheet">  
    <!-- Jquery UI Css -->
    <link href="{{ asset('public/assets/css/jquery-ui.min.css') }}"  media="all" type="text/css" rel="stylesheet"/>
    <!-- DateTimePicker Css -->
    <link href="{{ asset('public/assets/css/jquery.datetimepicker.min.css') }}" rel="stylesheet" media="all" type="text/css" />

    <!-- Waves Effect Css -->
    <link href="{{ asset('public/assets/css/waves.min.css') }}" rel="stylesheet">
    <!-- Animation Css -->
    <link href="{{ asset('public/assets/css/animate.min.css') }}" rel="stylesheet">
    <!-- JQuery DataTable Css -->
    <link href="{{ asset('public/assets/css/dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom Css -->
    <link href="{{ asset('public/assets/css/admin.bsb.min.css') }}" rel="stylesheet" media="all"> 
    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{ asset('public/assets/css/themes/all-themes.css') }}" rel="stylesheet" media="all"> 
 
    <!-- Jquery Core Js -->
    <script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
</head>

<body class="theme-indigo">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader pl-size-xl">
                <div class="spinner-layer pl-indigo">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p></p>
        </div>
    </div>
    <!-- #END# Page Loader -->


    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->

    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="{{ url('admin/dashboard') }}">{{ (!empty($app->title)?$app->title:null) }} - <small>{{ (!empty($app->description)?$app->description:null) }}</small></a>  
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right"> 
                    <!-- Notifications -->
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">email</i>
                            <span class="label-count">{{ !empty(Notify::message())?count(Notify::message()):null }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">{{ Lang::label('Message') }}</li>
                            <li class="body">
                                <ul class="menu">
                                    @if (!empty(Notify::message()))
                                        @foreach(Notify::message() as $message)
                                        <li>
                                            <a href="{{  url("admin/message/details/$message->id/inbox") }}">
                                                    <img class="icon-circle bg-light-green" src="{{ asset($message->photo?$message->photo:"public/assets/images/icons/user.png") }}"  /> 
                                                <div class="menu-info">
                                                    <h4>{{ $message->sender }} <small>{{ $message->date }}</small></h4>
                                                    <p>{{ $message->subject }}</p> 
                                                </div>
                                            </a>
                                        </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="{{ url('admin/message/inbox') }}">{{ Lang::label('View All') }}</a>
                            </li>
                        </ul>
                    </li>
                    @if (app()->environment() == 'demo')
                    <li><a class="btn btn-block btn-lg btn-success" href="https://1.envato.market/ck-parking">Buy now</a></li>
                    @endif
                    <!-- #END# Notifications --> 
                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
 
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info bg-indigo">
                <div class="image">
                    <img src="{{ asset(!empty(Auth::user()->photo)?Auth::user()->photo:'public/assets/images/icons/user.png') }}" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }}</div>
                    <div class="email">{{ Auth::user()->email }}</div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="{{ url('admin/setting/profile') }}"><i class="material-icons">person</i>Profile</a></li>
                            <li role="seperator" class="divider"></li>
                            <li><a href="{{ url('logout') }}"><i class="material-icons">input</i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list"> 
                    <li class="active hidden">Initial Active Item</li>

                    <!-- HOME -->
                    <li class="{{ ((Request::is('') || Request::is('admin/dashboard'))?'active':null) }}">
                        <a href="{{ url('admin/dashboard') }}">
                            <i class="material-icons">home</i>
                            <span>{{ Lang::label('Home') }}</span>
                        </a>
                    </li>

                    <!-- CLIENTS -->
                    <li class="{{ ((Request::is('*/client/*'))?'active':null) }}">
                        <a href="{{ url('admin/client/list/') }}">
                            <i class="material-icons">people</i>
                            <span>{{ Lang::label('Clients') }}</span>
                        </a>
                    </li> 

                    <!-- PARKING ZONES -->
                    <li class="{{ ((Request::is('*/place/*'))?'active':null) }}">
                        <a href="{{ url('admin/place/list/') }}">
                            <i class="material-icons">place</i>
                            <span>{{ Lang::label('Parking Zones') }}</span>
                        </a>
                    </li> 
 
                    <!-- VEHICLE TYPES -->
                    <li class="{{ ((Request::is('*/vehicle_type/*'))?'active':null) }}">
                        <a href="{{ url('admin/vehicle_type/list/') }}">
                            <i class="material-icons">directions_car</i>
                            <span>{{ Lang::label('Vehicle Types') }}</span>
                        </a>
                    </li> 
  
                    <!-- PRICES -->
                    <li class="{{ ((Request::is('*/price/*'))?'active':null) }}">
                        <a href="{{ url('admin/price/list/') }}">
                            <i class="material-icons">monetization_on</i>
                            <span>{{ Lang::label('Prices') }}</span>
                        </a>
                    </li> 
  
                    <!-- PROMO CODES -->
                    <!--<li class="{{ ((Request::is('*/promocode/*'))?'active':null) }}">
                        <a href="{{ url('admin/promocode/list/') }}">
                            <i class="material-icons">card_giftcard</i>
                            <span>{{ Lang::label('Promo Codes') }}</span>
                        </a>
                    </li> -->
  
                    <!-- EMAIL HISTORY -->
                    <!-- <li class="{{ ((Request::is('*/email/*'))?'active':null) }}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">email</i>
                            <span>{{ Lang::label('Email History') }}</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="{{ ((Request::is('*/email/new'))?'active':null) }}">
                                <a href="{{ url('admin/email/new/') }}">{{ Lang::label('New Email') }}</a>
                            </li> -->
                            <!-- <li class="{{ ((Request::is('*/email/bulk'))?'active':null) }}">
                                <a href="{{ url('admin/email/bulk/') }}">{{ Lang::label('Bulk Email') }}</a>
                            </li> -->
                            <!-- <li class="{{ ((Request::is('*/email/list'))?'active':null) }}">
                                <a href="{{ url('admin/email/list/') }}">{{ Lang::label('Email History') }}</a>
                            </li>

                            @roles("superadmin")
                            <li class="{{ ((Request::is('*/email/setting'))?'active':null) }}">
                                <a href="{{ url('admin/email/setting/') }}">{{ Lang::label('Setting') }}</a>
                            </li>
                            @endif
                        </ul>
                    </li>  -->

                    <li class="{{ ((Request::is('*/sms/*'))?'active':null) }}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">message</i>
                            <span>{{ Lang::label('SMS History') }}</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="{{ ((Request::is('*/sms/new'))?'active':null) }}">
                                <a href="{{ url('admin/sms/new/') }}">{{ Lang::label('New SMS') }}</a>
                            </li>
                            <li class="{{ ((Request::is('*/sms/list'))?'active':null) }}">
                                <a href="{{ url('admin/sms/list/') }}">{{ Lang::label('SMS History') }}</a>
                            </li> 
                            @roles("superadmin")
                            <li class="{{ ((Request::is('*/sms/setting'))?'active':null) }}">
                                <a href="{{ url('admin/sms/setting/') }}">{{ Lang::label('Setting') }}</a>
                            </li>
                            @endif
                        </ul>
                    </li> 

                    <li class="{{ ((Request::is('*/booking/*'))?'active':null) }}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">import_contacts</i>
                            <span>{{ Lang::label('Bookings') }}</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="{{ ((Request::is('*/booking/form'))?'active':null) }}">
                                <a href="{{ url('admin/booking/form/') }}">{{ Lang::label('New Booking') }}</a>
                            </li>
                            <li class="{{ ((Request::is('*/booking/today'))?'active':null) }}">
                                <a href="{{ url('admin/booking/today/') }}">{{ Lang::label('Today\'s Booking') }}</a>
                            </li>  
                            <li class="{{ ((Request::is('*/booking/current'))?'active':null) }}">
                                <a href="{{ url('admin/booking/current/') }}">{{ Lang::label('Active Booking') }}</a>
                            </li>  
                            <li class="{{ ((Request::is('*/booking/paid'))?'active':null) }}">
                                <a href="{{ url('admin/booking/paid/') }}">{{ Lang::label('Paid Booking') }}</a>
                            </li>  
                            <li class="{{ ((Request::is('*/booking/not_paid'))?'active':null) }}">
                                <a href="{{ url('admin/booking/not_paid/') }}">{{ Lang::label('Unpaid Booking') }}</a>
                            </li>  
                            <li class="{{ ((Request::is('*/booking/list'))?'active':null) }}">
                                <a href="{{ url('admin/booking/list/') }}">{{ Lang::label('Bookings') }}</a>
                            </li>  
                        </ul>
                    </li>
  
                    <li class="{{ ((Request::is('admin/report'))?'active':null) }}">
                        <a href="{{ url('admin/report') }}">
                            <i class="material-icons">pie_chart</i>
                            <span>{{ Lang::label('Reports') }}</span>
                        </a>
                    </li>  

                    <li class="header">{{ Lang::label('APPLICATION') }}</li>

                    <li class="{{ ((Request::is('*/message/*'))?'active':null) }}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons col-light-green">donut_large</i><span>{{ Lang::label('Message') }}</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="{{ ((Request::is('*/message/new'))?'active':null) }}">
                                <a href="{{ url('admin/message/new/') }}">{{ Lang::label('New Message') }}</a>
                            </li> 
                            <li class="{{ ((Request::is('*/message/inbox'))?'active':null) }}">
                                <a href="{{ url('admin/message/inbox/') }}">{{ Lang::label('Inbox Message') }}</a>
                            </li>  
                            <li class="{{ ((Request::is('*/message/sent'))?'active':null) }}">
                                <a href="{{ url('admin/message/sent/') }}">{{ Lang::label('Sent Message') }}</a>
                            </li> 
                        </ul>
                    </li>

                    @roles('superadmin') 
                    <li class="{{ ((Request::is('admin/language/*'))?'active':null) }}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons col-light-orange">donut_large</i>
                            <span>{{ Lang::label('Language') }}</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="{{ ((Request::is('admin/language/setting'))?'active':null) }}">
                                <a href="{{ url('admin/language/setting') }}">{{ Lang::label('Setting') }}</a>
                            </li> 
                            <li class="{{ ((Request::is('admin/language/label'))?'active':null) }}">
                                <a href="{{ url('admin/language/label') }}">{{ Lang::label('Label') }}</a>
                            </li> 
                        </ul>
                    </li>
                    <li class="{{ ((Request::is('*/user/*'))?'active':null) }}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons col-light-blue">donut_large</i>
                            <span>{{ Lang::label('User') }}</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="{{ ((Request::is('*/user/new'))?'active':null) }}">
                                <a href="{{ url('admin/user/new/') }}">{{ Lang::label('New User') }}</a>
                            </li>
                            <li class="{{ ((Request::is('*/user/list'))?'active':null) }}">
                                <a href="{{ url('admin/user/list/') }}">{{ Lang::label('Users') }}</a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ ((Request::is('*/setting/*'))?'active':null) }}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons col-amber">donut_large</i>
                            <span>{{ Lang::label('Setting') }}</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="{{ ((Request::is('*/setting/profile'))?'active':null) }}">
                                <a href="{{ url('admin/setting/profile') }}">{{ Lang::label('Profile') }}</a>
                            </li>
                            <li class="{{ ((Request::is('*/setting/app'))?'active':null) }}">
                                <a href="{{ url('admin/setting/app/') }}">{{ Lang::label('Application') }}</a>
                            </li> 
                        </ul>
                    </li>  
                    @endroles 

                    <li class="{{ ((Request::is('*/logout/*'))?'active':null) }}">
                        <a href="{{ url('logout') }}">
                            <i class="material-icons col-red">donut_large</i>
                            <span>{{ Lang::label('Logout') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">{{ (!empty($app->footer)?$app->footer:null) }}</div>
                <div class="version"><b>{{ Lang::label('Email') }}: </b> {{ (!empty($app->email)?$app->email:null) }}</div>
                <div class="version"><b>{{ Lang::label('Phone') }}: </b> {{ (!empty($app->phone)?$app->phone:null) }}</div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar --> 
    </section>
  
    <section class="content">  
        <div class="body"> 
            @include('alert::alert')
            @yield('content')
        </div>
    </section>
 
    <!-- Bootstrap Core Js -->
    <script src="{{ asset('public/assets/js/bootstrap.min.js') }}"></script>
    <!-- Jquery UI -->
    <script src="{{ asset('public/assets/js/jquery-ui.min.js') }}"></script>
    <!-- Select2  Js -->
    <script src="{{ asset('public/assets/js/select2.min.js') }}"></script>
    <!-- Tag Input  Js -->
    <script src="{{ asset('public/assets/js/bootstrap-tagsinput.min.js') }}"></script>
    <!-- Slimscroll Js -->
    <script src="{{ asset('public/assets/js/jquery.slimscroll.js') }}"></script>
    <!-- Waves Effect Js -->
    <script src="{{ asset('public/assets/js/waves.min.js') }}"></script>
    <!-- Datetimepicker Js -->
    <script src="{{ asset('public/assets/js/jquery.datetimepicker.full.min.js') }}"></script>
    <!-- DataTable Js -->
    <script src="{{ asset('public/assets/js/dataTables.min.js') }}"></script> 
    <!-- Validation Js -->
    <script src="{{ asset('public/assets/js/jquery.validate.min.js') }}"></script>
    <!-- Admin Js -->
    <script src="{{ asset('public/assets/js/admin.bsb.js') }}"></script> 
    <!-- Custom Js -->
    <script src="{{ asset('public/assets/js/script.js') }}"></script> 
    <!-- Page Scripts -->
    @yield('scripts')
    @yield('alert')
</body>
</html>