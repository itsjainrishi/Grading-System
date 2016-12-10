<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Student;
use App\Courses;
use Auth;
use Illuminate\Support\Facades\Input;
use Excel;
use App\Marks;

class StaffController extends Controller
{
   
	public function __construct()
    {
        $this->middleware('staff');
    }

    public function upload($id)
    {
    	return view('Staff.pages.upload')->with('id', $id);
    }

    public function upload_marks($id)
    {
     
        if (Input::hasfile('marks')) 
        { 
            $data = Excel::load(Input::file('marks'))->get();
   
            foreach ($data as $key => $value) {
                        
                         foreach ($value as $value_key => $value_value) {
                            foreach ($value_value as $value_value_key => $value_value_value) {
                                // echo ($value_value_key)."<br>";
                                $keys[] = $value_value_key;
                            }
                            break;
                        }
                        break;  
                }

                
                $i=0;
                $j=0;
                $k=0;
                $marks = array();
                
                foreach ($data as $key => $row) {
                        
                    foreach ($row as $row_key => $row_value) {
                        
                        foreach ($row_value as $row_value_key => $row_value_value) {
                                
                                
                                $weightage[$i][$row_value_key] = $row_value_value;

                            }
                            
                                $i++;
                    }
                }

                // dd($weightage);
                foreach ($weightage as $row) {
                    $k=0;
                    $count = count($row);

                    for ($j=2; $j<$count; $j++) {
                        
                        $key = $keys[$j];
                        
                        $marks[$k] = $row[$key];
                            $k++;
                        
                    }
                        
                            $mark = json_encode($marks);
                            // dd($mark);
                            // $new = $row[$keys[0]];    
                            
                        $sql = \DB::table('marks')->insert([
                        'clg_id' => $row[$keys[0]],
                        'course_id' => $id ,
                        'name' => $row[$keys[1]],
                        'marks' => $mark,
                        'total' => '0',
                        'grade' => '0',
                    ]);

                
                 }
                return redirect()->route('course.marks', ['CSE', 'se']);
        }
	}

	public function grades($stream, $id)
	{
		$info = \DB::table('students')->join('marks','students.id','marks.clg_id')->select('marks.clg_id','marks.name','marks.total', 'marks.grade')->where('stream',$stream)->where('course_id',$id)->get();
        $info = json_decode($info,true);
                               
                        $i=0;
                        foreach ($info as $key => $value) {
                        
                                 $student[$i]['clg_id'] = $value['clg_id'];
                                 $student[$i]['name'] = $value['name'];
                                 $student[$i]['total'] = $value['total'];
                                 $student[$i]['grade'] = $value['grade'];
                                 $i++; 
                        }
                
            return view('Staff.pages.grades')->with('student',$student);
	}

	public function report($stream, $id)
	{
		$info = \DB::table('students')->join('marks','students.id','marks.clg_id')->select('marks.clg_id','marks.name','marks.total', 'marks.grade')->where('stream',$stream)->where('course_id',$id)->get();
        $info = json_decode($info,true);

        $max = \DB::table('students')->join('marks','students.id','marks.clg_id')->where('stream',$stream)->where('course_id',$id)->max('total');
        $min = \DB::table('students')->join('marks','students.id','marks.clg_id')->where('stream',$stream)->where('course_id',$id)->min('total');
       
       	$avg = \DB::table('students')->join('marks','students.id','marks.clg_id')->where('stream',$stream)->where('course_id',$id)->avg('total');
       
                        $countaplus=$counta=$countbplus=$countb=$countcplus=$countc=$countd=$countf=0;

                        foreach ($info as $key => $value) {
                        
                        	if($value['grade']== 'A+')
                        		$countaplus++;
                        	elseif($value['grade']== 'A')
                        		$counta++;
                        	elseif($value['grade']== 'B+')
                        		$countbplus++;
                        	elseif($value['grade']== 'B')
                        		$countb++;
                        	elseif($value['grade']== 'C+')
                        		$countcplus++;
                        	elseif($value['grade']== 'C')
                        		$countc++;
                        	elseif($value['grade']== 'D')
                        		$countd++;
                        	elseif($value['grade']== 'F')
                        		$countf++;

                        }
                
            return view('Staff.pages.report', compact('countaplus','counta','countbplus','countb','countcplus','countc', 'countd', 'countf', 'max', 'min', 'avg','id'));
	}

	public function overreport($id)
	{
		$info = \DB::table('students')->join('marks','students.id','marks.clg_id')->select('marks.clg_id','marks.name','marks.total', 'marks.grade')->where('course_id',$id)->get();
        $info = json_decode($info,true);

        $max = \DB::table('students')->join('marks','students.id','marks.clg_id')->where('course_id',$id)->max('total');
        $min = \DB::table('students')->join('marks','students.id','marks.clg_id')->where('course_id',$id)->min('total');
       
       	$avg = \DB::table('students')->join('marks','students.id','marks.clg_id')->where('course_id',$id)->avg('total');
       
                        $countaplus=$counta=$countbplus=$countb=$countcplus=$countc=$countd=$countf=0;

                        foreach ($info as $key => $value) {
                        
                        	if($value['grade']== 'A+')
                        		$countaplus++;
                        	elseif($value['grade']== 'A')
                        		$counta++;
                        	elseif($value['grade']== 'B+')
                        		$countbplus++;
                        	elseif($value['grade']== 'B')
                        		$countb++;
                        	elseif($value['grade']== 'C+')
                        		$countcplus++;
                        	elseif($value['grade']== 'C')
                        		$countc++;
                        	elseif($value['grade']== 'D')
                        		$countd++;
                        	elseif($value['grade']== 'F')
                        		$countf++;

                        }

                        $stocksTable = \Lava::DataTable();  
            $stocksTable->addStringColumn('Grade')
            ->addNumberColumn('Number of students');

            $stocksTable->addRow(['A+', $countaplus]);
            $stocksTable->addRow(['A', $counta]);
            $stocksTable->addRow(['B+', $countbplus]);
            $stocksTable->addRow(['B', $countb]);
            $stocksTable->addRow(['C+', $countcplus]);
            $stocksTable->addRow(['C', $countcplus]);
            $stocksTable->addRow(['D', $countd]);

            $chart = \Lava::ColumnChart('Stocks', $stocksTable, ['title' => 'Software Engineering']);

            $gradesTable = \Lava::DataTable();  
            $gradesTable->addStringColumn('Grade')
            ->addNumberColumn('Number of students');

            $gradesTable->addRow(['A+', $countaplus]);
            $gradesTable->addRow(['A', $counta]);
            $gradesTable->addRow(['B+', $countbplus]);
            $gradesTable->addRow(['B', $countb]);
            $gradesTable->addRow(['C+', $countcplus]);
            $gradesTable->addRow(['C', $countcplus]);
            $gradesTable->addRow(['D', $countd]);

            $chart = \Lava::LineChart('Grades', $gradesTable, ['title' => 'Software Engineering']);
                
            return view('Staff.pages.overreport', compact('countaplus','counta','countbplus','countb','countcplus','countc', 'countd', 'countf', 'max', 'min', 'avg','id'));
	}
}
