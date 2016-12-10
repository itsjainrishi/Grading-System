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
                                        
                                        @for($i=0; $i<count($student); $i++)
                                        
                                        <tr class="odd gradeX">
                                            
                                            <td>{{$student[$i]['clg_id']}}</td>
                                            <td>{{$student[$i]['name']}}</td>
                                            
                                            @for($j=0; $j<count($student[$i]['marks']); $j++)
                                            
                                            <td>{{$student[$i]['marks'][$j]}}</td>
                                            
                                            @endfor    
                                        </tr>

                                        @endfor
                                        
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

@endsection