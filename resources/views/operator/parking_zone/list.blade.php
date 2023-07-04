@extends('operator.template')
 
@section('content')
<!-- Exportable Table -->
<div class="row clearfix">
    <div class="col-sm-12">
        <div class="card">
            <div class="header">
                <h2>
                    {{ $title }}
                </h2>
            </div> 

            <div class="body"> 
                <div class="xtable-responsive">
                    <table class="table table-condensed table-striped dataTable-place">
                        <thead>
                            <tr> 
                                <th>{{ Lang::label('SL No.') }}</th>
                                <th>{{ Lang::label('Name') }}</th>
                                <th>{{ Lang::label('Address') }}</th>
                                <th>{{ Lang::label('Limit') }}</th>
                                <th>{{ Lang::label('Status') }}</th>
                                <th>{{ Lang::label('Action') }}</th> 
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($places as $place)
                            <tr> 
                                <td>{{ ($loop->index+1) }}</td>
                                <td>{{ $place->name }}</td>
                                <td>{{ $place->address }}</td>
                                <td>{{ $place->limit }}</td>
                                <td>{!! (($place->status==1)?("<span class='label label-success'>Active</span>"):("<span class='label label-success'>Deactive</span>")) !!}</td>
                                <td><a href="{{ url('operator/parking_zone/'.$place->id) }}" class="btn btn-xs btn-success waves-effect"><i class="material-icons">remove_red_eye</i></a></td>
                            </tr>
                            @endforeach
                        </tbody>  
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- #END# Exportable Table -->
@endsection
 