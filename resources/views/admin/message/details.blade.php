@extends('admin.template')

@section('content')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    {{ $title }}
                </h2>
            </div>

            <div class="body">
                <dl class="dl-horizontal">
                    <dt>{{ Lang::label('Sender') }}</dt><dd>{{ $message->sender }}</dd>
                    <dt>{{ Lang::label('Receiver') }}</dt><dd>{{ $message->receiver }}</dd>
                    <dt>{{ Lang::label('Date') }}</dt><dd>{{ $message->datetime }}</dd>
                    <dt>{{ Lang::label('Subject') }}</dt><dd>{{ $message->subject }}</dd>
                    <dt>{{ Lang::label('Message') }}</dt><dd><p>{{ $message->message }}</p></dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
