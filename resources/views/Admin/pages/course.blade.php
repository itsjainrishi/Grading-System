@extends('Admin.layouts.app')

@section('content')
<link href="{{ URL::to('/') }}/css/home.css" rel="stylesheet">
<link href="{{ URL::to('/') }}/css/styles.css" rel="stylesheet">

    <div class="container-home" style="margin-top: 2%;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">      
            <div class="panel-body">
                
                @foreach($courses as $key => $value)
                <a href="{{ route('admin.marks', array('id' => $value['course_id'])) }}">
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-green">
                            <div class="panel-body">
                                <i class="fa fa-bar-chart-o fa-5x"></i>
                                <img src="{{ URL::to('/') }}/images/{{$value['course_name']}}.jpg" style="object-fit: 'contain'; height: 100%; width: 100%;">
                            </div>
                            <div class="panel-footer back-footer-green">
                                {{$value['course_name']}}

                            </div>
                        </div>
                    </div>
                   </a>

                @endforeach   
                
            </div>
        </div>
    </div>
</div>
@endsection