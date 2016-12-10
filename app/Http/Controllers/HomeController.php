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

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
      if(Auth::user()->role=='student')
      {
        $info = Student::where('id',Auth::user()->clg_id)->get(array('stream', 'batch', 'semester'))->first();
        
        $subjects = Courses::where('batch',$info->batch)->where('semester',$info->semester)->get(['stream','course_id']);

        
        foreach ($subjects as $subject) {
            
            $streams = json_decode($subject['stream'],true);
            
            foreach ($streams as $stream) {

                if($stream == $info->stream)
                {            
                    $courses[] = $subject->course_id;
                    break;
                }    

            }

       }

        foreach ($courses as $course) {
            
            $results[] = Courses::where('course_id',$course)->get()->first();

        }
            // foreach ($results as $result) {
                
            //     $faculty_id = json_decode($result['faculty'],true);
            //     $result->faculty_name = User::where('id',$faculty_id)->get(['name'])->first();
            //     $result->faculty_name = $result->faculty_name['name'];
            // }
        
            return view('Students.pages.home')->with('courses',$results)->with('stream', $info->stream);
        }

        elseif(Auth::user()->role=='staff'){
               
                $results = null;
                $info = Courses::get(array('stream', 'faculty', 'course_id'));
                $info = json_decode($info,true);
                $i=0;

                foreach ($info as $key) {
                    $stream = json_decode($key['stream']);
                    
                    foreach ($stream as $stream_key) {
                        $faculty = json_decode($key['faculty']);
                    
                        foreach ($faculty as $faculty_key) {
                            if(Auth::user()->clg_id == $faculty_key)
                            {
                                $courses = Courses::where('course_id',$key['course_id'])->get()->first();
                                $results[$i]['course_id'] = $key['course_id'];
                                $results[$i]['course_name'] = $courses['course_name'];
                                $results[$i]['stream'] = $stream;
                                $results[$i]['batch'] = $courses['batch'];
                                $i++;
                            }
                            
                        }

                    }
                 }
                 return view('Staff.pages.home')->with('courses',$results);
            }

         elseif(Auth::user()->role=='admin')
         {
            $info = Marks::get(array('grade'))->first();
        
            return view('Admin.pages.home')->with('info', $info);
         }
    }

    public function upload_excel()
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
                        'course_id' => 'cn',
                        'name' => $row[$keys[1]],
                        'marks' => $mark,
                        'grade' => '0',
                    ]);

                
                 }
                return redirect()->route('course.marks', 'se');
        }



    }

    public function upload()
    {
        return view('upload');
    }

    

    public function addparameters()
    {
        return view('parameter');
    }

    public function show(Request $request, $id)
    {

        $count= sizeof($request->Assessment);

        for($i=0; $i<$count; $i++)
        {
            $assessment[$i][0] = $request->Weightage[$i];
            $assessment[$i][1] = $request->maxmarks[$i];
            $weightage[$request->Assessment[$i]] = $assessment[$i]; 
        }

        $weightage = json_encode($weightage);
       
       $sql = \DB::table('courses')->where('course_id','se')->update([
                        'weightage' => $weightage,
                    ]);
    }


    public function course()
    {
        $faculties = User::where('role', 'staff')->get(array('clg_id','name'));

        return view('addcourse')->with('faculties', $faculties);
    }

 protected function create(Request $request)
    {
        $this->validate($request, array(
            'course_id' => 'required|max:255|min:3',
            'course_name' => 'required|max:255|min:3',
            'stream' => 'required',
            'batch' => 'required',
            'sem' => 'required',
            'faculty_name' => 'required',
            'credits' => 'required|Integer',
        ));

        // $course = new Courses;
        $sql = \DB::table('courses')->insert([
        'course_id' => $request->course_id,
        'course_name' => $request->course_name,
        'stream' => json_encode($request->stream),
        'batch' => $request->batch,
        'semester' => $request->sem,
        'faculty' => json_encode($request->faculty_name),
        // $course->credits = $request->credits;
        'weightage' => 'null',
        ]);

        // $course->save();
        return redirect('home')->with('message','Successfully added!');
    }

    public function getmarks($id)
    {
        $info = \DB::table('students')->join('marks','students.id','marks.clg_id')->select('marks.clg_id','marks.name','marks.marks')->where('stream','CSE')->get();
        $info = json_decode($info,true);


        $weightage = Courses::where('course_id','se')->get(['weightage'])->first();
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
                        return view('marks')->with('weightage',$weightage)->with('student',$student);
    }

    public function generategrades()
    {
        $courses = Courses::where('batch','2014')->get(['course_id']);
        $totalcredits = 0;
        foreach ($courses as $key => $value) {
                $stud_marks = Marks::where('course_id', $value['course_id'])->get(array('clg_id','name','marks'));
                $stud_marks = json_decode($stud_marks,true);

                  $weightage = Courses::where('course_id',$value['course_id'])->get(['weightage'])->first();
                  $weightage = $weightage['weightage'];
                    
                  $weightage = json_decode($weightage,true);
                  
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
                            elseif ($d <= $value_value['marks'] &&  $value_value['marks'] < $c)
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

            $chart = \Lava::ColumnChart('Stocks', $stocksTable, ['title' => 'Stock Market Trends']);

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

            $chart = \Lava::LineChart('Grades', $gradesTable, ['title' => 'Grades Trends']);

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

             return view('grades', compact('aplus','a','bplus','b','cplus','c','d'));
    }

    public function normalize(Request $request)
    {

        $a = $request->a;
        $aplus = $request->aplus;
        $b = $request->b;
        $bplus = $request->bplus;
        $c = $request->c;
        $cplus = $request->cplus;
        $d = $request->d;
        $course_id = 'se';

        $countaplus=$counta=$countbplus=$countb=$countcplus=$countc=$countd=0;

        $marks = Marks::where('course_id', 'se')->where('total', '!=', '40')->get(array('clg_id', 'name', 'total'));
        $marks = json_decode($marks,true);

                    foreach ($marks as $key => $value) {
                            if($value['total'] >= $aplus)
                            {
                                    $sql = \DB::table('marks')->where('clg_id',$value['clg_id'])->where('course_id',$course_id)->update([
                                    'grade' => 'A+',
                                    ]); 
                                
                                 $countaplus++;
                  
                            }
                            elseif ($a <= $value['total'] &&  $value['total'] < $aplus)
                            {
                                $sql = \DB::table('marks')->where('clg_id',$value['clg_id'])->where('course_id',$course_id)->update([
                                    'grade' => 'A',
                                    ]);
                                
                                $counta++;
                                
                            }
                            elseif ($bplus <= $value['total'] &&  $value['total'] < $a)
                            {

                                
                                $sql = \DB::table('marks')->where('clg_id',$value['clg_id'])->where('course_id',$course_id)->update([
                                    'grade' => 'B+',
                                    ]);
                                
                                $countbplus++;
                               
                            }
                            elseif ($b <= $value['total'] &&  $value['total'] < $bplus)
                            {
                                 $sql = \DB::table('marks')->where('clg_id',$value['clg_id'])->where('course_id',$course_id)->update([
                                    'grade' => 'B',
                                    ]);
                                 $countb++;
                            }
                            elseif ($cplus <= $value['total'] &&  $value['total'] < $b)
                            {
                                $sql = \DB::table('marks')->where('clg_id',$value['clg_id'])->where('course_id',$course_id)->update([
                                    'grade' => 'C+',
                                    ]);
                                    $countcplus++;
                            }
                            elseif ($c <= $value['total'] &&  $value['total'] < $cplus)
                            {
                                    $sql = \DB::table('marks')->where('clg_id',$value['clg_id'])->where('course_id',$course_id)->update([
                                    'grade' => 'C',
                                    ]);
                                    $countc++;
                            }
                            elseif ($d <= $value['total'] &&  $value['total'] < $c)
                            {
                                $sql = \DB::table('marks')->where('clg_id',$value['clg_id'])->where('course_id',$course_id)->update([
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
            $stocksTable->addRow(['C', $countc]);
            $stocksTable->addRow(['D', $countd]);

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

            return response()->json($stocksTable);

    }
        
}