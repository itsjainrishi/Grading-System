@extends('Admin.layouts.app')

@section('content')
<link href="{{ URL::to('/') }}/css/home.css" rel="stylesheet">
<link href="{{ URL::to('/') }}/css/styles.css" rel="stylesheet">

@if(Auth::user()->role==='student')
        <div align="center">
            Unauthorised Access!
            <br>
            <a href="{{URL::to('/')}}">
                Go Back to Home.
            </a>
        </div>
@endif
    <div class="container-home" style="margin-top: 2%;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">      
            <div class="panel-body">
            
                <a href="{{ route('course.edit', array( 'id' => $id)) }}">
                <div class="pull-left">
                    <div class="container att" style="width:274px; height:80px;background-color:orange;" >
                        <h2 class="text-center">
                        <span class="glyphicon glyphicon-check"></span> Add Assessment Parameters </h2>
                    </div>
                </div>
                </a>
                
                <a href="{{ route('course.marks', array( 'stream' => $stream, 'id' => $id)) }}">
                <div class="pull-left">
                    <div class="container att"  style="width:274px; height:80px; float: left;  background-color:#eedd82">
                        <h2 class="text-center"><span class="glyphicon glyphicon-font">
                        </span> View Scores</h2>
                    </div>
                </div>
                </a>

                <a href="{{ route('course.grades', array( 'stream' => $stream, 'id' => $id)) }}">
                <div class="pull-left">
                    <div class="container att" style="width:274px; height:80px; float: left; background-color:#5f9ea0">
                        <h2 class="text-center"><span class="glyphicon glyphicon-duplicate">
                        </span> View Grades</h2>
                    </div>
                </div>
                </a>   

                <a href="{{ route('course.report', array( 'stream' => $stream, 'id' => $id)) }}">
                <div class="pull-left">
                    <div class="container att" style="width:274px;height:80px;background-color:pink">
                        <h2 class="text-center"><span class="glyphicon glyphicon-user">
                        </span> View Class Report </h2>
                    </div>
                </div>
                </a>

                <a href="{{ route('course.upload', array( 'id' => $id )) }}">
                <div class="pull-left">
                    <div class="container att" style="width:274px;height:80px;background-color:pink">
                        <h2 class="text-center"><span class="glyphicon glyphicon-user">
                        </span> Upload Marks </h2>
                    </div>
                </div>
                </a>
                              
                
            </div>
        </div>
    </div>
</div>
@endsection