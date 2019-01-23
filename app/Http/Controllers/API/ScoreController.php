<?php

namespace Jiri\Http\Controllers\API;

use Illuminate\Http\Request;
use Jiri\Http\Controllers\Controller;
use Jiri\Score;

class ScoreController extends Controller
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
           'implementation_id' => 'required|int',
           'user_id' => 'required|int',
           'score' => 'required|numeric|min:0|max:20'
        ]);
        
        $score = new Score();
        
        $score->implementation_id = $request->implementation_id;
        $score->user_id = $request->user_id;
        $score->score = $request->score;
        $score->comment = $request->comment;
        
        $score->save();
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

    /*
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Score $score)
    {
      $this->validate($request, [
        'implementation_id' => 'required|int',
        'user_id' => 'required|int',
        'score' => 'required|numeric|min:0|max:20'
      ]);
  
      $score->implementation_id = $request->implementation_id;
      $score->user_id = $request->user_id;
      $score->score = $request->score;
      $score->comment = $request->comment;
  
      $score->update();
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
}
