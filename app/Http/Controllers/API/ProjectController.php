<?php

namespace Jiri\Http\Controllers\API;

use Illuminate\Http\Request;
use Jiri\Event;
use Jiri\Http\Controllers\Controller;
use Jiri\Project;

class ProjectController extends Controller
{
    /*
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $data = Project::where('event_id', $id)->orderBy('ponderation_default', 'DESC')->get();
        
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
           'name' => 'required|max:255',
           'name_short' => 'required|max:8',
           'description' => 'required|max:1000',
           'ponderation_default' => 'required|int|min:0|max:100',
           'event_id' => 'required|int'
        ]);
        
        $project = new Project();
        
        $project->name = $request->name;
        $project->name_short = $request->name_short;
        $project->description = $request->description;
        $project->ponderation_default = $request->ponderation_default;
        $project->event_id = $request->event_id;
        
        $project->save();
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
    
    public function selectedProject($id, $project)
    {
        $data = Project::where('id', $project)->first();
        
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
    public function update(Request $request, $id, $project)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'name_short' => 'required|max:8',
            'description' => 'required|max:1000',
            'ponderation_default' => 'required|int|min:0|max:100',
            'event_id' => 'required|int'
        ]);
        
        $el = Project::where('id', $project)->first();
        
        $el->name = $request->name;
        $el->name_short = $request->name_short;
        $el->description = $request->description;
        $el->ponderation_default = $request->ponderation_default;
        $el->event_id = $request->event_id;
        
        $el->update();
    }

    /*
     * Remove the specified resource from storage.
     */
    public function destroy($id, $project)
    {
        $el = Project::where('id', $project)->first();
        $el->delete();
    }
}
