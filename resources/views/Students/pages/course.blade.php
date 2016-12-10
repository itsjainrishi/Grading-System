@extends('Admin.layouts.app')

@section('content')
<link href="{{ URL::to('/') }}/css/home.css" rel="stylesheet">
<link href="{{ URL::to('/') }}/css/styles.css" rel="stylesheet">

    <div class="container-home" style="margin-top: 2%;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">      
            <div class="panel-body">
            
                <div class="pull-left">
                    <div class="container att" style="width:350px; height:80px;background-color:orange;" >
                        <h2 class="text-center">
                        <span class="glyphicon glyphicon-check" style="margin-top: 10px"></span> Attendance </h2>
                    </div>
                </div>
                
                <a href="{{ route('course.score', array( 'id' => Auth::user()->clg_id, 'course_id' => $id)) }}">
                <div class="pull-left">
                    <div class="container att"  style="width:350px; height:80px; float: left;  background-color:#eedd82">
                        <h2 class="text-center"><span class="glyphicon glyphicon-font" style="margin-top: 10px">
                        </span> View Marks/Grades</h2>
                    </div>
                </div>
                </a>     
                
            </div>
        </div>
    </div>
</div>
@endsection