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
                    @if(!empty($content['status']))  
                    <li>
                        <button type="button" onClick="printContent('printArea')" class="btn btn-sm btn-info waves-effect">
                            <i class="material-icons">print</i>
                            <span>{{ Lang::label('Print') }}</span>
                        </button>
                    </li> 
                    @endif  
                </ul>
            </div> 

            <div class="body" id="printArea"> 
                 {!! (!empty($content['data'])?$content['data']:null) !!}
            </div>
        </div>
    </div>
</div>
@endsection 