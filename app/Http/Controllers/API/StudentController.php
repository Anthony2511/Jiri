<?php

namespace Jiri\Http\Controllers\API;

use Illuminate\Http\Request;
use Jiri\Http\Controllers\Controller;
use Jiri\Student;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keywords = $request->keywords;
        
        $query = Student::query();
        
        if ($request->has('keywords')) {
            $query->where(function ($query) use ($keywords) {
                $query->where('name', 'like', '%' . $keywords . '%')
                      ->orWhere('email', 'like', '%' . $keywords . '%');
            });
        }
        
        $data = $query->orderBy('created_at', 'DESC')->paginate(15)->appends(request()->except('page'));
        
        return response()->json($data);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required|max:255',
            'email' => 'required|email|max:255|unique:students'
        ]);
        
        $student = new Student();
        
        $student->name  = $request->name;
        $student->email = $request->email;
        
        $student->save();
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \Jiri\Student $student
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id, $student)
    {
        $data = Student::where('id', $student)->first();
        
        return response()->json($data);
    }
    
    // Get datas for a student
    public function getStudent($student) {
        $data = Student::where('id', $student)->first();
    
        return response()->json($data);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Jiri\Student $student
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Jiri\Student            $student
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:students,email,'.
                       $student->id,
            'is_active' => 'required|boolean'
        ]);
    
        $student->name = $request->name;
        $student->email = $request->email;
        $student->is_active = $request->is_active;
        $student->update();
    }
    
    /*
     * Remove the specified resource from storage.
     *
     * @param  \Jiri\Student $student
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->delete();
    }
}
