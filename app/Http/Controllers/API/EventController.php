<?php

namespace Jiri\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jiri\Event;
use Jiri\Http\Controllers\Controller;
use Jiri\Student;
use Jiri\User;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Event::query();
        
        if (Auth::user()->is_admin == 1)
        {
            $data = $query->orderBy('date_event', 'DESC')->with('students', 'users')->paginate(15)->appends(request()->except('page'));
            
            return response()->json($data);
        } else {
            $user = Auth::user()->id;
            
            $data = Auth::user()->events()->with('students', 'users')->paginate(15)->appends(request()->except('page'));
            
            return response()->json($data);
        }
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
            'course_name'  => 'required|max:255',
            'academic_year' => 'required|max:4|min:4',
            'exam_session' => 'required',
            'date_event' => 'required'
        ]);
        
        $event = new Event();
        
        $event->course_name  = $request->course_name;
        $event->academic_year = $request->academic_year;
        $event->exam_session = $request->exam_session;
        $event->date_event = $request->date_event;
        
        $event->save();
    }
    
    
    /**
     * Add member of jury to Event
     */
    public function addMemberToJury(Request $request, $id)
    {
        $this->validate($request, [
            'member_id' => 'required|int'
        ]);
        
        $event = Event::where('id', $id)->first();
    
        $event->users()->syncWithoutDetaching($request->member_id);
    }
    
    /**
     * Add student to Event
     */
    public function addStudent(Request $request, $id)
    {
        $this->validate($request, [
            'member_id' => 'required|int'
        ]);
        
        $event = Event::where('id', $id)->first();
        
        $event->students()->syncWithoutDetaching($request->member_id);
    }
    
    /**
     * Get members list for a jury
     */
    public function getMembersOfJuryList($id)
    {
        $event = Event::where('id', $id)->first();
        $data = $event->users()->get();
    
        return response()->json($data);
    }
    
    /*
     * Get students list for an event
     */
    public function getStudents($id)
    {
        $event = Event::where('id', $id)->first();
        // $data = $event->students()->with('implementations', 'implementations.project', 'implementations.scores')->get();
        
        $data = $event->students()
          ->with([
            'implementations' => function ($query) use ($id) {
              $query->where('event_id', $id);
            },
            'implementations.project',
            'implementations.scores',
            'performance' => function($query) use ($id) {
              $query->where('event_id', $id);
            }
          ])->get();
        
        return response()->json($data);
    }
    
    /*
     * Remove members to list for a jury
     */
    public function removeMember($id, $memberId)
    {
        $event = Event::where('id', $id)->first();
        $event->users()->detach($memberId);
    }
    
    /**
     * Remove student to list for a event
     */
    public function removeStudent($id, $memberId)
    {
        $event = Event::where('id', $id)->first();
        $event->students()->detach($memberId);
    }
    
    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::where('id', $id)
                ->with('students', 'users')
                ->first();
        
        if ($event != null)
        {
            return response()->json($event);
        } else {
            abort(404);
        }
    }
    
    /**
     * Search a member of jury for autocomplete select
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchMembersOfJury(Request $request)
    {
        $searchmembersofjury = $request->searchmembersofjury;
        
        $data = User::where('is_active', '1')
                      ->where(function ($query) use ($searchmembersofjury) {
                          $query->where('name', 'like', '%' . $searchmembersofjury . '%')
                                ->orWhere('email', 'like', '%' . $searchmembersofjury . '%');
                      })->get();
        
        return response()->json($data);
    }
    
    /*
     * Search students
     */
    public function searchStudents(Request $request)
    {
        $searchstudents = $request->searchstudents;
        
        $data = Student::where('is_active', '1')
                    ->where(function ($query) use ($searchstudents) {
                        $query->where('name', 'like', '%' . $searchstudents . '%')
                              ->orWhere('email', 'like', '%' . $searchstudents . '%');
                    })->get();
        
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
    
    /*
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $this->validate($request, [
            'course_name' => 'required|max:255',
            'academic_year' => 'int',
            'exam_session' => 'required|int',
            'status' => 'required|int',
            'date_event' => 'required|date',
        ]);
        
        $event->course_name = $request->course_name;
        $event->academic_year = $request->academic_year;
        $event->exam_session = $request->exam_session;
        $event->status = $request->status;
        $event->date_event = $request->date_event;
        $event->update();
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
