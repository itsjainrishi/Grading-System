@extends('Admin.layouts.app')

@section('content')
<link href="{{ URL::to('/') }}/css/home.css" rel="stylesheet">
<link href="{{ URL::to('/') }}/css/styles.css" rel="stylesheet">
<div class="container-home" style="margin-top: 2%;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">      
            <div class="panel-body">
            
            
                <a href="{{ route('grades.create') }}">
                <div class="pull-left">
                    <div class="container att" style="width:274px; height:80px;background-color:orange;" >
                        <h2 class="text-center">
                        <span class="glyphicon glyphicon-check"></span> Generate Result </h2>
                    </div>
                </div>
                </a>
                
                <a href="{{ route('admin.courses') }}">
                <div class="pull-left">
                    <div class="container att"  style="width:274px; height:80px; float: left;  background-color:#eedd82">
                        <h2 class="text-center"><span class="glyphicon glyphicon-font">
                        </span> View Result </h2>
                    </div>
                </div>
                </a>

                <a href="{{ route('course.create') }}">
                <div class="pull-left">
                    <div class="container att" style="width:274px; height:80px; float: left; background-color:#5f9ea0">
                        <h2 class="text-center"><span class="glyphicon glyphicon-duplicate">
                        </span> Add Courses </h2>
                    </div>
                </div>    
                </a>

                <a href="{{ url('/register') }}">
                <div class="pull-left">
                    <div class="container att" style="width:274px;height:80px;background-color:pink">
                        <h2 class="text-center"><span class="glyphicon glyphicon-user">
                        </span> Create Users </h2>
                    </div>
                </div>
                </a>
                              
                
            </div>
        </div>
    </div>
</div>
@endsection