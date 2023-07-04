@extends('website.template')

@section('content')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card"> 
            <div class="header">
                <h2>{{ $title }}</h2>
                <ul class="header-dropdown m-r--5">
                    <li>
                        <a href="#" title="Print" onclick="printContent('printMe')" class="btn btn-sm btn-info waves-effect">
                            <i class="material-icons">print</i>
                            <span>{{ Lang::label('Print') }}</span>
                        </a>
                    </li>  
                    <li>
                        <a href="{{ url('website/profile/edit') }}" class="btn btn-sm btn-warning waves-effect">
                            <i class="material-icons">edit</i>
                            <span>{{ Lang::label('Edit Profile') }}</span>
                        </a>
                    </li>   
                </ul>
            </div>  


            <div class="body" id="printMe">
                <div class="row">
                    <div class="col-sm-4 text-right">
                        <img src="{{ asset(!empty($client->vehicle_photo)?$client->vehicle_photo:'public/assets/images/icons/default.jpg') }}" alt="" class="thumbnail "  />
                    </div>
                    <div class="col-sm-8">
                        <blockquote>
                            <p>{{ $client->name }}
                                <br> <small>{{ $client->id_no }}</small>
                            </p> 
                        </blockquote>
                        <p> <i class="glyphicon glyphicon-envelope"></i> 
                            {{ $client->email }}
                            <br/> 
                            <i class="glyphicon glyphicon-calendar"></i> {{ $client->created_at }}
                            <br>
                            <i class="glyphicon glyphicon-map-marker"></i> {{ $client->address }} 
                            <br>
                            <i class="glyphicon glyphicon-info-sign"></i> {{ $client->note }} 
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="dataTable table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ Lang::label('Vehicle Photo') }}</th>
                                    <th>{{ Lang::label('Vehicle Licence') }}</th>
                                    <th>{{ Lang::label('Color') }}</th>
                                    <th>{{ Lang::label('Note') }}</th>
                                    <th>{{ Lang::label('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vehicles as $v)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td><a href="{{ url(!empty($v->photo)?$v->photo:'public/assets/images/icons/default.jpg') }}" target="_blank"><img src="{{ asset(!empty($v->photo)?$v->photo:'public/assets/images/icons/default.jpg') }}" alt="" width="50" /></a></td>
                                    <td>{{ $v->licence }}</td>
                                    <td>{{ $v->color }}</td>
                                    <td>{{ $v->note }}</td>
                                    <td>
                                        @if($v->status==1)
                                            <span class="label label-success">{{ Lang::label("Enabled") }}</span>
                                        @else
                                            <span class="label label-danger">{{ Lang::label("Disabled") }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
    // datatables
    $('.dataTable').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        searching:false,
        paging:false,
        buttons: [
            
        ],
    });
});
</script>
@endsection