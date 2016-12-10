@extends('Students.layouts.app')

@section('content')
<div class="container" style="margin-top: 2%;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading" style="background-color: #3cb371; color:#fff" >Report</div>
                <div class="panel-body">
                 
                <div>   
                    <label>MAX Marks:</label>
                    <span>{{ $max }}</span>
                </div>
                
                <div>
                    <label>MIN Marks:</label>
                    <span>{{ $min }}</span>
                </div>
                
                <div>
                    <label>Average Marks:</label>
                    <span>{{ $avg }}</span>
                </div>

                <div>
                <label>A+:</label>
                <span>{{ $countaplus }}</span>
                </div>

                <div>
                <label>A:</label>
                <span>{{ $counta }}</span>
                </div>

                <div>
                <label>B+:</label>
                <span>{{ $countbplus }}</span>
                </div>

                <div>
                <label>B:</label>
                <span>{{ $countb }}</span>
                </div>

                <div>
                <label>C+:</label>
                <span>{{ $countcplus }}</span>
                </div>

                <div>
                <label>C:</label>
                <span>{{ $countc }}</span>
                </div>

                <div>
                <label>D:</label>
                <span>{{ $countd }}</span>
                </div>

                <div>
                <label>F:</label>
                <span>{{ $countf }}</span>
                </div>
                </div>
            </div>
        </div>
    </div>
        
        <div class="pull-right" style="margin-right: 17%;">
        <a href="{{ route('course.overreport', array( 'id' => $id )) }}">
            <button type="button" class="btn btn-primary" style="background-color: #3cb371">
                View Overall Report
            </button>
        </a>
        </div>
</div>
@endsection
