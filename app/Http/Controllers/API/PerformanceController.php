<?php

namespace Jiri\Http\Controllers\API;

use Illuminate\Http\Request;
use Jiri\Http\Controllers\Controller;
use Jiri\Performance;

class PerformanceController extends Controller
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
   * @param  \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'calculated_score' => 'required|numeric',
      'manual_score'     => 'required|numeric',
      'student_id'       => 'required',
      'event_id'         => 'required'
    ]);
    
    $performance = new Performance();
    
    $performance->calculated_score = $request->calculated_score;
    $performance->manual_score     = $request->manual_score;
    $performance->student_id       = $request->student_id;
    $performance->event_id         = $request->event_id;
    
    $performance->save();
  }
  
  /**
   * Display the specified resource.
   *
   * @param  int $id
   *
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }
  
  /**
   * Show the form for editing the specified resource.
   *
   * @param  int $id
   *
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }
  
  /*
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id, $student)
  {
    $this->validate($request, [
      'manual_score' => 'required|numeric',
      'student_id'   => 'required',
      'event_id'     => 'required'
    ]);
    
    $el = Performance::where('event_id', $id)->where('student_id', $student)->first();
    
    $el->calculated_score = $request->calculated_score;
    $el->manual_score = $request->manual_score;
      
    $el->update();
  }
  
  /**
   * Remove the specified resource from storage.
   *
   * @param  int $id
   *
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}
