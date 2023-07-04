@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
           {{ $title }}
        @endcomponent
    @endslot

    {{-- Subcopy --}}
    @slot('subcopy')
        {!! $message !!}
    @endslot


    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            {{ $footer }}
        @endcomponent
    @endslot
@endcomponent