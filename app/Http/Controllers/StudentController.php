<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Courses;

class StudentController extends Controller
{
    
	public function getmarks($id, $course_id)
	{
		$info = \DB::table('students')->join('marks','students.id','marks.clg_id')->select('marks.clg_id','marks.name','marks.marks','marks.total','marks.grade')->where('clg_id', $id)->where('course_id', $course_id)->first();


        $weightage = Courses::where('course_id',$course_id)->get(['weightage'])->first();
        $weightage = $weightage['weightage'];
        
        $weightage = json_decode($weightage,true);
        $total=0;
        foreach ($weightage as $key => $value_value) {
                      $factor[] = $value_value[0]/$value_value[1];
                  }
                                
                                $arraymarks = $info->marks;
                                $arraymarks = json_decode($arraymarks);
                                $j=0;
                            foreach ($arraymarks as $array) {
                                    
                                    $student['marks'][$j] = $array;
                                    if($info->total!=0)
                                    	$total = $info->total;
                                    else
                                    	$total = $array * $factor[$j] + $total;
                                    $j++;
                            }
                                 $student['clg_id'] = $info->clg_id;
                                 $student['name'] = $info->name;
                                 $student['total'] = $total;
                                 $student['grade'] = $info->grade;

        $min = \DB::table('students')->join('marks','students.id','marks.clg_id')->where('course_id',$course_id)->max('total');
        $max = \DB::table('students')->join('marks','students.id','marks.clg_id')->where('course_id',$course_id)->min('total');
       
       	$avg = \DB::table('students')->join('marks','students.id','marks.clg_id')->where('course_id',$course_id)->avg('total');                      
        
        return view('Students.pages.marks', compact('student', 'weightage', 'min', 'max', 'avg'));

	}

}
