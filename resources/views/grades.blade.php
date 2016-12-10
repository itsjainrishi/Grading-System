@extends('Staff.layouts.app')

@section('content')
<link href="{{ URL::to('/') }}/css/home.css" rel="stylesheet">
<div class="{{ URL::to('/') }}/container-home" style="margin-top: 2%;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">      
            
            <div class="row">
            <span style="font-weight: bold;font-size:30px;margin-left: 28%" >Click on the Course to view grade report</span><br><br>
                @php $color = ['green', 'red', 'blue', 'brown'] @endphp
                @for($i=0; $i<count($courses); $i++)
                    
                    <a href="{{ route('grades.show', array( 'id' => $courses[$i]['course_id'])) }}">
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-{{$color[$i]}}">
                            <div class="panel-body">
                                <i class="fa fa-bar-chart-o fa-5x"></i>
                                <img src="{{ URL::to('/') }}/images/{{$courses[$i]['course_name']}}.jpg" style="object-fit: 'contain'; height: 100%; width: 100%;">
                            </div>
                            <div class="panel-footer back-footer-{{$color[$i]}}">
                                {{$courses[$i]['course_name']}} <br>
                                {{$courses[$i]['stream'][$i]}}

                            </div>
                        </div>
                    </div>
                   </a>
                   @endfor
            </div>
                
        
        </div>
    </div>
</div>
 <link href="{{ URL::to('/') }}/css/styles.css" rel="stylesheet">
@endsection