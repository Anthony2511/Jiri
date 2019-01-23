<?php

namespace Jiri\Http\Controllers\API;

use Illuminate\Http\Request;
use Jiri\Http\Controllers\Controller;
use Jiri\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keywords = $request->keywords;
    
        $query = User::query();
    
        if ($request->has('keywords')) {
            $query->where(function ($query) use ($keywords) {
                $query->where('name', 'like', '%' . $keywords . '%')
                      ->orWhere('email', 'like', '%' . $keywords . '%');
            });
        }
    
        $data = $query->where('is_admin', 0)->orderBy('created_at', 'DESC')->paginate(15)->appends(request()->except('page'));
    
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
            'name'  => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'company' => 'required|max:255'
        ]);
        
        // Generate a password for the new users
        $password = User::generatePassword();
    
        $user = new User();
    
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->company = $request->company;
        $user->is_admin = 0;
        $user->is_active = 1;
        $user->password = $password;
    
        $user->save();
        
        User::sendWelcomeEmail($user);
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
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.
                       $user->id,
            'company' => 'required',
            'is_active' => 'required|boolean'
        ]);
    
        $user->name = $request->name;
        $user->email = $request->email;
        $user->company = $request->company;
        $user->is_active = $request->is_active;
        
        $user->update();
    }

    /*
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
    }
}
