@extends('operator.template')

@section('styels')
<!-- Morris Chart Css-->
<link href="{{ url('plugins/morrisjs/morris.css') }}" rel="stylesheet" />
@endsection

@section('content')
<div class="container-fluid"> 
    <!-- Widgets --> 
    <div class="row clearfix"> 
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-pink hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">book</i>
                </div>
                <div class="content">
                    <div class="text text-uppercase">{{ Lang::label('Total Booking') }}</div>
                    <div class="number count-to" data-from="0" data-to="{{ !empty($reportCounter->total)?$reportCounter->total:0 }}" data-speed="15" data-fresh-interval="20"></div>
                </div>  
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-cyan hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">today</i>
                </div>
                <div class="content">
                    <div class="text text-uppercase">{{ Lang::label('TODAY\'S BOOKING') }}</div>
                    <div class="number count-to" data-from="0" data-to="{{ !empty($reportCounter->todays_booking)?$reportCounter->todays_booking:0 }}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-light-green hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">playlist_add_check</i>
                </div>
                <div class="content">
                    <div class="text text-uppercase">{{ Lang::label('Active Booking') }}</div> 
                    <div class="number count-to" data-from="0" data-to="{{ !empty($reportCounter->current)?$reportCounter->current:0 }}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-blue hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">offline_pin</i>
                </div>
                <div class="content">
                    <div class="text text-uppercase">{{ Lang::label('Release Booking') }}</div> 
                    <div class="number count-to" data-from="0" data-to="{{ !empty($reportCounter->releases)?$reportCounter->releases:0 }}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-green hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">attach_money</i>
                </div>
                <div class="content">
                    <div class="text text-uppercase">{{ Lang::label('Paid Booking') }}</div> 
                    <div class="number count-to" data-from="0" data-to="{{ !empty($reportCounter->paid)?$reportCounter->paid:0 }}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-orange hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">money_off</i>
                </div>
                <div class="content">
                    <div class="text text-uppercase">{{ Lang::label('Unpaid Booking') }}</div> 
                    <div class="number count-to" data-from="0" data-to="{{ !empty($reportCounter->current)?$reportCounter->current:0 }}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-red hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">monetization_on</i>
                </div>
                <div class="content">
                    <div class="text text-uppercase">{{ Lang::label('Net Amount') }}</div> 
                    <div class="number count-to" data-from="0" data-to="{{ !empty($reportCounter->amount)?$reportCounter->amount:0 }}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-orange hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">person_add</i>
                </div>
                <div class="content">
                    <div class="text text-uppercase">{{ Lang::label('Total Client') }}</div> 
                    <div class="number count-to" data-from="0" data-to="{{ !empty($clientCounter->total_client)?$clientCounter->total_client:0 }}" data-speed="1000" data-fresh-interval="20"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Widgets -->

    <!-- Chart Js --> 
    <div class="row clearfix">
        <!-- Line Chart -->
        <div class="col-sm-12">
            <div class="card">
                <div class="header">
                    <h2 class="text-uppercase">{{ Lang::label('This Year Booking') }}</h2> 
                </div>
                <div class="body"> 
                    <canvas id="line_chart" height="180" width="416"></canvas>
                </div>
            </div>
        </div>
        <!-- #END# Line Chart -->
    </div>

    <div class="row clearfix">
        <!-- From the Beginning -->
        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
            <div class="card">
                <div class="header">
                    <h2 class="text-uppercase">{{ Lang::label('From the Beginning') }}</h2> 
                </div>
                <div class="body">
                    <canvas id="pie_chart" style="height:345px"></canvas>
                </div>
            </div>
        </div>
        <!-- #END# From the Beginning -->

        <!-- Task Info -->
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="header">
                    <h2 class="text-uppercase">{{ Lang::label('Recent Messages') }}</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-hover dashboard-task-infos">
                            <thead>
                                <tr>
                                    <th>{{ Lang::label('Sender') }}</th>
                                    <th>{{ Lang::label('Subject') }}</th>
                                    <th>{{ Lang::label('Action') }}</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($messages as $message)
                                <tr>
                                    <td>
                                        <img src="{{ asset($message->photo?$message->photo:"public/assets/images/icons/user.png") }}" width="40" height="30" />
                                    </td>
                                    <td>
                                        <strong>{{ $message->sender }}</strong><br>
                                        {{ $message->subject }}</td>
                                    <td><a href="{{  url("admin/message/details/$message->id/inbox") }}" class="btn btn-xs btn-primary waves-effect"><i class="material-icons">remove_red_eye</i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Task Info -->
    </div> 
</div>   
@endsection


@section('scripts')
<!-- Jquery CountTo Plugin Js -->
<script src="{{ url('public/assets/js/jquery.countTo.js') }}"></script>
<!-- Chart Js  -->
<script src="{{ url('public/assets/js/Chart.bundle.min.js') }}"></script>
<script type="text/javascript">
$(function () {
    //Widgets count
    $('.count-to').countTo(); 

    // Chart Js
    new Chart(document.getElementById("line_chart").getContext("2d"), getChartJs('line'));
    new Chart(document.getElementById("pie_chart").getContext("2d"), getChartJs('pie'));
});

function getChartJs(type) {
    var config = null;

    if (type === 'line') {
        config = {
            type: 'line',
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                datasets: [{
                    label: "{{ Lang::label('Booking') }}",
                    data: [
                        {{ !empty($booking->jan)?$booking->jan:0 }}, 
                        {{ !empty($booking->feb)?$booking->feb:0 }}, 
                        {{ !empty($booking->mar)?$booking->mar:0 }}, 
                        {{ !empty($booking->apr)?$booking->apr:0 }}, 
                        {{ !empty($booking->may)?$booking->may:0 }}, 
                        {{ !empty($booking->jun)?$booking->jun:0 }}, 
                        {{ !empty($booking->jul)?$booking->jul:0 }}, 
                        {{ !empty($booking->aug)?$booking->aug:0 }}, 
                        {{ !empty($booking->sep)?$booking->sep:0 }}, 
                        {{ !empty($booking->oct)?$booking->oct:0 }}, 
                        {{ !empty($booking->nov)?$booking->nov:0 }}, 
                        {{ !empty($booking->decx)?$booking->decx:0 }} 
                    ],
                    borderColor: 'rgba(0, 188, 212, 0.75)',
                    backgroundColor: 'rgba(0, 188, 212, 0.3)',
                    pointBorderColor: 'rgba(0, 188, 212, 0)',
                    pointBackgroundColor: 'rgba(0, 188, 212, 0.9)',
                    pointBorderWidth: 1
                }, 
                {
                    label: "{{ Lang::label('Client') }}",
                    data: [
                        {{ !empty($client->jan)?$client->jan:0 }}, 
                        {{ !empty($client->feb)?$client->feb:0 }}, 
                        {{ !empty($client->mar)?$client->mar:0 }}, 
                        {{ !empty($client->apr)?$client->apr:0 }}, 
                        {{ !empty($client->may)?$client->may:0 }}, 
                        {{ !empty($client->jun)?$client->jun:0 }}, 
                        {{ !empty($client->jul)?$client->jul:0 }}, 
                        {{ !empty($client->aug)?$client->aug:0 }}, 
                        {{ !empty($client->sep)?$client->sep:0 }}, 
                        {{ !empty($client->oct)?$client->oct:0 }}, 
                        {{ !empty($client->nov)?$client->nov:0 }}, 
                        {{ !empty($client->decx)?$client->decx:0 }} 
                    ],
                    borderColor: 'rgba(180, 150, 99, 0.75)',
                    backgroundColor: 'rgba(180, 150, 99, 0.3)',
                    pointBorderColor: 'rgba(180, 150, 99, 0)',
                    pointBackgroundColor: 'rgba(180, 150, 99, 0.9)',
                    pointBorderWidth: 1
                }, 
                {
                    label: "{{ Lang::label('Amount') }}",
                    data: [
                        {{ !empty($amount->jan)?$amount->jan:0 }}, 
                        {{ !empty($amount->feb)?$amount->feb:0 }}, 
                        {{ !empty($amount->mar)?$amount->mar:0 }}, 
                        {{ !empty($amount->apr)?$amount->apr:0 }}, 
                        {{ !empty($amount->may)?$amount->may:0 }}, 
                        {{ !empty($amount->jun)?$amount->jun:0 }}, 
                        {{ !empty($amount->jul)?$amount->jul:0 }}, 
                        {{ !empty($amount->aug)?$amount->aug:0 }}, 
                        {{ !empty($amount->sep)?$amount->sep:0 }}, 
                        {{ !empty($amount->oct)?$amount->oct:0 }}, 
                        {{ !empty($amount->nov)?$amount->nov:0 }}, 
                        {{ !empty($amount->decx)?$amount->decx:0 }} 
                    ],
                    borderColor: 'rgba(233, 30, 99, 0.75)',
                    backgroundColor: 'rgba(233, 30, 99, 0.3)',
                    pointBorderColor: 'rgba(233, 30, 99, 0)',
                    pointBackgroundColor: 'rgba(233, 30, 99, 0.9)',
                    pointBorderWidth: 1
                }]
            },
            options: {
                responsive: true, 
                legend: {
                    display: true 
                }
            }
        }
    } 
    else if (type === 'pie') {
        config = {
            type: 'pie',
            data: {
                datasets: [{ 
                    data: [
                        {{ !empty($reportCounter->paid)?$reportCounter->paid:0 }},
                        {{ !empty($reportCounter->not_paid)?$reportCounter->not_paid:0 }},
                        {{ !empty($reportCounter->current)?$reportCounter->current:0 }},
                        {{ !empty($reportCounter->releases)?$reportCounter->releases:0 }},
                        {{ !empty($reportCounter->todays_booking)?$reportCounter->todays_booking:0 }},
                        {{ !empty($reportCounter->total)?$reportCounter->total:0 }},
                    ],
                    backgroundColor: [
                        "rgb(233, 30, 99)",
                        "rgb(255, 193, 7)",
                        "rgb(0, 188, 212)",
                        "rgb(255, 150, 100)",
                        "rgb(100, 108, 212)",
                        "rgb(139, 195, 74)"
                    ],
                }],
                labels: [
                    "Paid Booking",
                    "Not Paid Booking",
                    "Active Booking",
                    "Release Booking",
                    "Todays Booking",
                    "Total Booking"
                ]
            },
            options: {
                responsive: true, 
                legend: {
                    display: true 
                }
            }
        }
    }
    return config;
}

</script>
@endsection
