<?php

namespace Jiri\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jiri\Http\Controllers\Controller;
use Jiri\Implementation;
use Jiri\Score;
use Jiri\Student;

class ImplementationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'project_id' => 'required|int',
            'student_id' => 'required|int',
            'event_id' => 'required|int',
            'ponderation' => 'required|int'
        ]);
        
        $implementation = new Implementation();
        
        $implementation->project_id = $request->project_id;
        $implementation->student_id = $request->student_id;
        $implementation->event_id = $request->event_id;
        $implementation->url_project = $request->url_project;
        $implementation->url_repo = $request->url_repo;
        $implementation->ponderation = $request->ponderation;
        
        $implementation->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    
    /* Get implementations for current student */
    public function getImplementations($id, $student)
    {
        $data = Implementation::where('event_id', $id)->where('student_id', $student)->with('project', 'score')->get();
        
        return response()->json($data);
    }
    
    public function getImplementationsForSpecificUser($id, $student)
    {
        $data = Implementation::where('event_id', $id)
                  ->with([
                      'project',
                      'score' => function ($query) {
                          $query->where('user_id', Auth::id());
                      }
                  ])
                  ->where('event_id', $id)
                  ->where('student_id', $student)
                  ->get();
        
        
        
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $implementation)
    {
        $this->validate($request, [
          'project_id' => 'required|int',
          'student_id' => 'required|int',
          'event_id' => 'required|int',
          'ponderation' => 'required|int'
        ]);
        
        $el = Implementation::where('id', $implementation)->first();
        
        $el->project_id = $request->project_id;
        $el->url_project = $request->url_project;
        $el->url_repo = $request->url_repo;
        $el->ponderation = $request->ponderation;
        
        $el->update();
    }

    /*
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $implementation)
    {
      $el = Implementation::where('id', $implementation)->first();
      
      $el->delete();
    }
}
