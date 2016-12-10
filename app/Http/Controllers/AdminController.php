<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Courses;
use App\Marks;
use App\User;
use App\Student;
use Auth;
use Illuminate\Support\Facades\Input;
use Excel;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function generategrades()
    {
        $courses = Courses::where('batch','2014')->get(['course_id','course_name']);
        $totalcredits = 0;
        foreach ($courses as $key => $value) {
                $stud_marks = Marks::where('course_id', $value['course_id'])->get(array('clg_id','name','marks'));
                $stud_marks = json_decode($stud_marks,true);

                  $weightage = Courses::where('course_id',$value['course_id'])->get(['weightage'])->first();
                  $weightage = $weightage['weightage'];
                    
                  $weightage = json_decode($weightage,true);
                  if(!$weightage || !$stud_marks)
                  {
                  	return redirect('home');
                  }
                  
                  foreach ($weightage as $key => $value_value) {
                      $factor[] = $value_value[0]/$value_value[1];
                  }
                    $i=0;
                  foreach ($stud_marks as $key) {
                                
                                $id = $key['clg_id'];
                                $arraymarks = $key['marks'];
                                $arraymarks = json_decode($arraymarks);
                                $name = $key['name'];

                                
                                $marks = 0;
                            for($j=0; $j<count($arraymarks); $j++) {
                                    
                                    $marks = $marks + $arraymarks[$j]*$factor[$j];
                                    
                            }

                            $marks = round($marks);

                            $sql = \DB::table('marks')->where('clg_id',$id)->where('course_id',$value['course_id'])->update([
                                'total' => $marks,
                                ]);

                            if($marks<40)
                            {
                                $sql = \DB::table('marks')->where('clg_id',$id)->where('course_id',$value['course_id'])->update([
                                'grade' => 'F',
                                ]);
                            }
                            else
                            {
                                $total[$i]['clg_id'] = $id;
                                $total[$i]['name'] = $name; 
                                $total[$i]['marks'] = $marks;
                                $i++;
                            }

                            
                        }

                        $passmark = array();
                       
                        foreach ($total as $key) {
                            $passmark[] = $key['marks'];
                        }
                        sort($passmark);
                        $n = count($passmark);
                        
                        $first = round(.25 * ($n + 1)) - 1;
                        $third = round(.75 * ($n + 1)) - 1;
                        
                        $first = $passmark[$first];
                        $third = $passmark[$third];
                        $iqr   = $third - $first;
                        $upper = $third + 1.5* $iqr;
                        $lower = $first - 1.5* $iqr;
                        $count = count($total);
                        
                        $mean = 0;
                        for($j=0; $j<$count; $j++) {
                            // echo $total[$j]['marks']."<br>";
                            if($total[$j]['marks']<$lower || $total[$j]['marks']>$upper)
                            {
                                if($total[$j]['marks']<$lower)
                                {
                                    $sql = \DB::table('marks')->where('clg_id',$total[$j]['clg_id'])->where('course_id',$value['course_id'])->update([
                                    'grade' => 'D',
                                    ]);       
                                }
                                elseif ($total[$j]['marks']>$upper) {
                                    $sql = \DB::table('marks')->where('clg_id',$total[$j]['clg_id'])->where('course_id',$value['course_id'])->update([
                                    'grade' => 'A+',
                                    ]);   
                                }

                                for($i=$j; $i<count($total); $i++)
                                {
                                    $total[$i]['clg_id'] = $total[$i+1]['clg_id'];
                                    $total[$i]['name'] = $total[$i+1]['name'];
                                    $total[$i]['marks'] = $total[$i+1]['marks'];
                                }
                                $j--;
                                $count = $count-1;
                            }
                            else
                            {
                                $mean = $total[$j]['marks'] + $mean;
                            }
                            
                        }
                        $mean = $mean/$count;
                        $i=0;
                        foreach ($total as $key) {
                            $passmark[$i] = $key['marks'];
                            $i++;
                        }
        
                        foreach($passmark as $key => $num) 
                            $devs[$key] = pow($num - $mean, 2);
                            
                            $std = sqrt(array_sum($devs) / (count($devs) - 1));
                     
                    $aplus = round($mean + 1.5 * $std);
                    $a = round($mean + 1 * $std);
                    $bplus = round($mean + 0.5 * $std);
                    $b = round($mean);
                    $cplus = round($mean - 0.5 * $std);
                    $c = round($mean - 1 * $std);
                    $d = round($mean - 1.5 * $std);
                    $countaplus=0;$counta=0;$countbplus=0;$countb=0;$countcplus=0;$countc=0;$countd=0;
                    
                    foreach ($total as $key => $value_value) {

                            if($value_value['marks'] > $aplus)
                            {
                                    $sql = \DB::table('marks')->where('clg_id',$value_value['clg_id'])->where('course_id',$value['course_id'])->update([
                                    'grade' => 'A+',
                                    ]); 
                                
                                 $countaplus++;
                  
                            }
                            elseif ($a <= $value_value['marks'] &&  $value_value['marks'] < $aplus)
                            {
                                $sql = \DB::table('marks')->where('clg_id',$value_value['clg_id'])->where('course_id',$value['course_id'])->update([
                                    'grade' => 'A',
                                    ]);
                                
                                $counta++;
                                
                            }
                            elseif ($bplus <= $value_value['marks'] &&  $value_value['marks'] < $a)
                            {

                                
                                $sql = \DB::table('marks')->where('clg_id',$value_value['clg_id'])->where('course_id',$value['course_id'])->update([
                                    'grade' => 'B+',
                                    ]);
                                
                                $countbplus++;
                               
                            }
                            elseif ($b <= $value_value['marks'] &&  $value_value['marks'] < $bplus)
                            {
                                 $sql = \DB::table('marks')->where('clg_id',$value_value['clg_id'])->where('course_id',$value['course_id'])->update([
                                    'grade' => 'B',
                                    ]);
                                 $countb++;
                            }
                            elseif ($cplus <= $value_value['marks'] &&  $value_value['marks'] < $b)
                            {
                                $sql = \DB::table('marks')->where('clg_id',$value_value['clg_id'])->where('course_id',$value['course_id'])->update([
                                    'grade' => 'C+',
                                    ]);
                                    $countcplus++;
                            }
                            elseif ($c <= $value_value['marks'] &&  $value_value['marks'] < $cplus)
                            {
                                    $sql = \DB::table('marks')->where('clg_id',$value_value['clg_id'])->where('course_id',$value['course_id'])->update([
                                    'grade' => 'C',
                                    ]);
                                    $countc++;
                            }
                            elseif ('40' <= $value_value['marks'] &&  $value_value['marks'] < $c)
                            {
                                $sql = \DB::table('marks')->where('clg_id',$value_value['clg_id'])->where('course_id',$value['course_id'])->update([
                                    'grade' => 'D+',
                                    ]);
                                    $countd++;
                            }
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

            $chart = \Lava::ColumnChart('GradesB', $stocksTable, ['title' => 'Bar Chart']);

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

            $chart = \Lava::LineChart('GradesL', $gradesTable, ['title' => 'Line Chart']);

            // $credits = Courses::where('course_id', $value['course_id'])->get(array('credits'))->first();
            // $credits = $credits['credits'];
            // $grades = Marks::where('course_id', $value['course_id'])->get(array('clg_id', 'grade'));
             
            // $totalcredits = $credits + $totalcredits; 
            //  $grades = json_decode($grades,true);
                         
            //     foreach ($grades as $key => $value) {
            //         switch ($value['grade']) {
            //             case 'A+':
            //                      $value['gpa'] = 10 * $credits + $value['gpa'];
            //                 break;
            //             case 'A':
            //                      $value['gpa'] = 9 * $credits + $value['gpa'];
            //                 break;
            //             case 'B+':
            //                      $value['gpa'] = 8 * $credits + $value['gpa'];
            //                 break;
            //             case 'B':
            //                      $value['gpa'] = 7 * $credits + $value['gpa'];
            //                 break;
            //             case 'C+':
            //                      $value['gpa'] = 6 * $credits + $value['gpa'];
            //                 break;
            //             case 'C':
            //                      $value['gpa'] = 5 * $credits + $value['gpa'];
            //                 break;
            //             case 'D':
            //                      $value['gpa'] = 4 * $credits + $value['gpa'];
            //                 break;
            //             case 'F':
            //                      $value['gpa'] = 3 * $credits + $value['gpa'];
            //                 break;
                        
            //             default:
            //                 $value['gpa'] = 0;
            //                 break;
            //         }

            //         echo $value['gpa']."<br>";
            //     }
            }
             return view('grades', compact('courses'));
    }

    public function getgrades($id)
    {
    	$info = \DB::table('marks')->select('clg_id','name','total', 'grade')->where('course_id',$id)->get();
        $info = json_decode($info,true);

        $max = \DB::table('marks')->where('course_id',$id)->max('total');
        $min = \DB::table('marks')->where('course_id',$id)->min('total');
       
       	$avg = \DB::table('marks')->where('course_id',$id)->avg('total');
       	
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
                
            return view('Admin.pages.report', compact('countaplus','counta','countbplus','countb','countcplus','countc', 'countd', 'countf', 'max', 'min', 'avg','id'));
    }

    public function normalize($id)
    {
    	$info = \DB::table('marks')->select('clg_id','name','total', 'grade')->where('course_id',$id)->get();
        $info = json_decode($info,true);

       	$avg = \DB::table('marks')->where('course_id',$id)->avg('total');

       	$marks = \DB::table('marks')->select('total')->where('course_id',$id)->where('grade', '!=', 'F')->get();
       	$marks = json_decode($marks,true);

       	foreach ($marks as $key => $value) {
       		$factor[$key] = pow($value['total']-$avg, 2);
       	}
       		
       		$std = sqrt(array_sum($factor) / (count($factor) - 1));
	      
                    $aplus = round($avg + 1.5 * $std);
                    $a = round($avg + 1 * $std);
                    $bplus = round($avg + 0.5 * $std);
                    $b = round($avg);
                    $cplus = round($avg - 0.5 * $std);
                    $c = round($avg - 1 * $std);
                    $d = round($avg - 1.5 * $std);

       
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
                
            return view('Admin.pages.normalize', compact('aplus','a','bplus','b','cplus','c', 'd', 'id'));	
    }

    public function getmarks()
    {
    	$courses = Courses::where('batch','2014')->get(['course_id','course_name']);
        $courses = json_decode($courses,true);


        return view('Admin.pages.course')->with('courses', $courses);
    }

    public function marks($id)
    {
    	 $info = \DB::table('students')->join('marks','students.id','marks.clg_id')->select('marks.clg_id','marks.name','marks.marks')->where('course_id',$id)->get();
        $info = json_decode($info,true);


        $weightage = Courses::where('course_id', $id)->get(['weightage'])->first();
        $weightage = $weightage['weightage'];
        
        $weightage = json_decode($weightage,true);
                        $i=0;
                         foreach ($info as $key) {
                                
                                $arraymarks = $key['marks'];
                                $arraymarks = json_decode($arraymarks);
                                $j=0;
                            foreach ($arraymarks as $array) {
                                    
                                    $student[$i]['marks'][$j] = $array;
                                    $j++;
                            }
                            
                            $i++;
                        }
                            
                            $i=0;
                        foreach ($info as $key => $value) {
                        
                                 $student[$i]['clg_id'] = $value['clg_id'];
                                 $student[$i]['name'] = $value['name'];
                                 $i++; 
                        }
                // dd($student);
            return view('Staff.pages.marks')->with('weightage',$weightage)->with('student',$student);
    }
}

