@extends('layouts.app')

@section('content')
<link href="css/home.css" rel="stylesheet">
<div class="container-home" style="margin-top: 2%;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
                

                @if(Auth::user()->role==='student')

                <div class="row">
                @php $color = ['green', 'red', 'blue', 'brown'] @endphp
                @for($i=0; $i<count($courses); $i++)
                    <a href="#">
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-{{$color[$i]}}">
                            <div class="panel-body">
                                <i class="fa fa-bar-chart-o fa-5x"></i>
                                <img src="images/{{$courses[$i]->course_name}}.jpg" style="object-fit: 'contain'; height: 100%; width: 100%;">
                            </div>
                            <div class="panel-footer back-footer-{{$color[$i]}}">
                                {{$courses[$i]->course_name}}

                            </div>
                        </div>
                    </div>
                   </a>
                   @endfor
                    
                @elseif(Auth::user()->role==='staff')
                    <div class="container att" style="width:274px; height:80px;background-color:orange;" >
                    <h2 class="text-center"><span class="glyphicon glyphicon-check"></span> Attendance</h2>
                    </div>

                    <div class="container att"  style="width:274px; height:80px; float: left;  background-color:#eedd82">
                    <h2 class="text-center"><span class="glyphicon glyphicon-font"></span> Grades</h2>
                    </div>

                    <div class="container att" style="width:274px; height:80px; float: left; background-color:#5f9ea0">
                    <h2 class="text-center"><span class="glyphicon glyphicon-duplicate"></span> Courses</h2>
                    </div>

                    <div class="container att" style="width:274px;height:80px;background-color:pink">
                    <h2 class="text-center"><span class="glyphicon glyphicon-user"></span> Account</h2>
                    </div>
                
                @elseif(Auth::user()->role==='admin')
                  hie
                  <div class="container att" style="width:274px;height:80px;background-color:pink">
                    <h2 class="text-center"><span class="glyphicon glyphicon-user"></span> Account</h2>
                    </div>
                    
                   <div class="panel-body">
                        <div class="widget-wrapper" id="orange">
                            <a href="http://lms.bml.edu.in/my" class="btn-block">
                                <div class="widget widget-stats bg-orange">                             
                                    <div class="widget-top-sec clearfix">
                                    <div class="pull-right widget-icon">
                                            <img class="img-responsive" src="images/User.png" alt=Users />
                                        </div>
                        
                                        <div class="stats-title pull-left">Create new users</div>
                                                                
                                </div>
                                </div>
                            </a>
                        </div>

                        <div class="widget-wrapper" id="blue">
                            <a href="http://lms.bml.edu.in/my" class="btn-block">
                                <div class="widget widget-stats bg-smalt-blue">                             
                                    <div class="widget-top-sec clearfix">
                                        
                                        <div class="pull-right widget-icon">
                                            <img class="img-responsive" src="images/User.png" alt=Users />
                                        </div>
                                        <div class="stats-title pull-left">Add Courses</div>
                                        
                                    </div>                        
                                </div>
                            </a>
                        </div>
                
                </div>
                @endif
        
        </div>
    </div>
</div>
 <link href="css/styles.css" rel="stylesheet">
@endsection