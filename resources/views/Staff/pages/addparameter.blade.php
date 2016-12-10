@extends('Students.layouts.app')

@section('content')

<div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2" style="margin-top: 35px">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="background-color: #3cb371; color:#fff">Add course</div>
                        <div class="panel-body">
              <form role="form" id="upload" method="POST" action="{{ route('course.param', array( 'id' => $id)) }}">
                 {{ csrf_field() }}

                <div class="form-group{{ $errors->has('Assessment-1') ? ' has-error' : '' }}" id="Assessment-1">
                  <label>Assessment-1</label>
                  <input type="text" name="Assessment[]" id="title" class="form-control">
                  @if ($errors->has('Assessment-1'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('Assessment-1') }}</strong>
                                    </span>
                                @endif
                  <span class="placeholder">Enter Assessment-1.</span><br>
                  <input type="text" name="Weightage[]" id="title" class="form-control">Weightage</input>
                  <input type="text" name="maxmarks[]" id="title" class="form-control">Max-marks</input>
                </div><br>

                <div class="form-group" id="Assessment-2"></div>
                <div class="form-group" id="Assessment-3"></div>
                <div class="form-group" id="Assessment-4"></div>
                <div class="form-group" id="Assessment-5"></div>
                <div class="form-group" id="Assessment-6"></div>
                <div class="form-group" id="Assessment-7"></div>
                <div class="form-group" id="Assessment-8"></div>
                <div class="form-group" id="Assessment-9"></div>

                <button id="field" type = "button" class="btn btn-info" style="background-color: #3cb371;color: #fff"> Add more parameters <MAIN></MAIN></button>
                <button id="field1" type = "button" class="btn btn-info" style="background-color: #3cb371;color: #fff">Add another parameter</button><br><br>
        
                <div class="form-group">
                  <button type="submit" id="submit" class="btn btn-default form-control" style="background-color: #3cb371;color: #fff">Submit</button>
                </div>
                
              </form>
                      </div>
                    </div>
                </div>
            </div>
</div>
<script type="text/javascript">
  $("#field1").hide();
  var i=2;

  $("#field").click(function(){
    $("#field").hide();
      $("#field1").show();
      $("#Assessment-1").after('<label>Assessment-2</label><input type="text" name="Assessment[]" id="title" class="form-control"><span class="placeholder">Enter Assessment-2.</span><br><input type="text" name="Weightage[]" id="title" class="form-control">Weightage</input><input type="text" name="maxmarks[]" id="title" class="form-control">Max-marks</input><br>');
     });

  $("#field1").click(function(){
    $("#field").hide();
      $("#Assessment-" + i).append('<label>Assessment-' + (i+1) + '</label><input type="text" name="Assessment[]" id="title" class="form-control"><span class="placeholder">Enter Assessment-' + (i+1) + '.</span><br><input type="text" name="Weightage[]" id="title" class="form-control">Weightage</input><input type="text" name="maxmarks[]" id="title" class="form-control">Max-marks</input><br>');
      i=i+1;
     });


</script>

@endsection