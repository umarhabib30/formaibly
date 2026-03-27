@extends($activeTemplate.'layouts.frontend')

@section('content')


    @if($sections != null)
        @foreach(json_decode($sections) as $sec)
            @includeIf($activeTemplate.'sections.'.$sec)
        @endforeach
    @endif
@endsection
