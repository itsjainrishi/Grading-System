<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Courses;
use Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role=='admin')
        {
            $faculties = User::where('role', 'staff')->get(array('clg_id','name'));

            return view('Admin.pages.addcourse')->with('faculties', $faculties);
        }

        return redirect('home');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, array(
            'course_id' => 'required|max:255|min:2',
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
        'credits' => $request->credits,
        'weightage' => 'null',
        ]);

        return redirect()->route('course.show');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($stream, $id)
    {
        if(Auth::user()->role=='student')
            return view('Students.pages.course')->with('id', $id);
        elseif (Auth::user()->role=='staff') {
            return view('Staff.pages.course')->with('id', $id)->with('stream', $stream);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::user()->role=='staff')
        {
            return view('Staff.pages.addparameter')->with('id', $id);
        }

        return redirect('home');
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function addparam(Request $request, $id)
    {
        $count= sizeof($request->Assessment);
            
        for($i=0; $i<$count; $i++)
        {
            $assessment[$i][0] = $request->Weightage[$i];
            $assessment[$i][1] = $request->maxmarks[$i];
            $weightage[$request->Assessment[$i]] = $assessment[$i]; 
        }

        $weightage = json_encode($weightage);
       
       $sql = \DB::table('courses')->where('course_id',$id)->update([
                        'weightage' => $weightage,
                    ]);

       return redirect('home');
    }

     public function getmarks($stream, $id)
    {
        $info = \DB::table('students')->join('marks','students.id','marks.clg_id')->select('marks.clg_id','marks.name','marks.marks')->where('stream',$stream)->where('course_id',$id)->get();
        $info = json_decode($info,true);

        $student = array(); 

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
