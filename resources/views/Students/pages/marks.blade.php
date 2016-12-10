@extends('Students.layouts.app')

@section('content')

<div class="row">
                <div class="col-md-12" style="margin-left:15%;width: 70%;margin-top:5%;">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr style="background-color: #3cb371;color: #fff">
                                            <th>Roll-no</th>
                                            <th>Name</th>
                                            
                                            @foreach ($weightage as $key => $value)
                                            <th>{{$key}}</th>
                                            @endforeach
                                            <th>Total</th>
                                            <th>Grade</th>
                                        </tr>
                                            
                                        <tr>
                                            <th></th>
                                            <th></th>                                        
                                            @foreach ($weightage as $key => $value)   
                                            @foreach ($value as $value_key => $value_value)
                                                
                                            @endforeach
                                            <th>({{$value_value}})</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="odd gradeX">
                                            
                                            <td>{{$student['clg_id']}}</td>
                                            <td>{{$student['name']}}</td>
                                            
                                            @for($j=0; $j<count($student['marks']); $j++)
                                            
                                            <td>{{$student['marks'][$j]}}</td>
                                            
                                            @endfor    
                                            <td>{{$student['total']}}</td>
                                            <td>{{$student['grade']}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default" style="width: 50%;margin-left: 27%" align="center">
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
                    <label>Average:</label>
                    <span>{{ $avg }}</span>
                </div>
            </div>
        </div>
    </div>
            </div>

@endsection