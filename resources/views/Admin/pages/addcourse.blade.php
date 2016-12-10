@extends('Students.layouts.app')

@section('content')
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2" style="margin-top: 35px">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="background-color: #3cb371; color:#fff">Add course</div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST"
                                  action="{{ URL::to('/') }}/course">
                                {{csrf_field()}}
                                
                                <div class="form-group{{ $errors->has('course_id') ? ' has-error' : '' }}">
                                    <label for="course_name" class="col-md-4 control-label">Course ID</label>
                                    <div class="col-md-6">
                                        <input id="course_name" type="text" class="form-control" name="course_id"
                                               value="{{ old('course_id') }}" autofocus required>
                                        @if ($errors->has('course_id'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('course_id') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('course_name') ? ' has-error' : '' }}">
                                    <label for="course_name" class="col-md-4 control-label">Course Name</label>
                                    <div class="col-md-6">
                                        <input id="course_name" type="text" class="form-control" name="course_name"
                                               value="{{ old('course_name') }}" required>
                                        @if ($errors->has('course_name'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('course_name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="faculty_id" class="col-md-4 control-label">Faculty</label>
                                    <div class="col-md-6">
                                        <label>
                                            <select name="faculty_name[]" class="form-control"
                                                    value="{{ old('faculty_id') }}" required>
                                                <option value=""></option>
                                                @foreach($faculties as $faculty)
                                                    <option value="{{$faculty->clg_id}}">{{$faculty->name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('faculty_name'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('faculty_name') }}</strong>
                                    </span>
                                        @endif
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="batch" class="col-md-4 control-label">Batch</label>
                                    <div class="col-md-6">
                                        <label>
                                            <select name="batch" class="form-control"
                                                    value="{{ old('batch') }}" required>
                                                <option value=""></option>
                                                <option value="2014">2014</option>
                                                <option value="2015">2015</option>
                                                <option value="2016">2016</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="sem" class="col-md-4 control-label">Semester</label>
                                    <div class="col-md-6">
                                        <label>
                                            <select name="sem" class="form-control"
                                                    value="{{ old('sem') }}" required>
                                                <option value=""></option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="stream" class="col-md-4 control-label">Stream</label>
                                    <label style="margin-left: 2%">
                                        <input type="checkbox" name="stream[]" value="CSC"
                                               class="form-control">CSC
                                        <input type="checkbox" name="stream[]" value="CSE"
                                               class="form-control">CSE
                                        <input type="checkbox" name="stream[]" value="ECE"
                                               class="form-control">ECE
                                        <input type="checkbox" name="stream[]" value="ME"
                                               class="form-control">ME
                                        <input type="checkbox" name="stream[]" value="CE"
                                               class="form-control">CE
                                    </label>
                                </div>

                                <div class="form-group{{ $errors->has('credits') ? ' has-error' : '' }}">
                                    <label for="credits" class="col-md-4 control-label">Credits</label>
                                    <div class="col-md-6">
                                        <input id="credits" type="number" class="form-control" name="credits"
                                               value="{{ old('credits') }}" required min="0" max="100">
                                        @if ($errors->has('credits'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('credits') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary" style="background-color: #3cb371">
                                            Add course
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection