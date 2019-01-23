<?php

namespace Jiri\Http\Controllers\Auth;

use Jiri\User;
use Jiri\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
  use RegistersUsers;
  
  /**
   * Where to redirect users after registration.
   *
   * @var string
   */
  protected $redirectTo = '/dashboard';
  
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest');
  }
  
  /**
   * Get a validator for an incoming registration request.
   *
   * @param  array $data
   *
   * @return \Illuminate\Contracts\Validation\Validator
   */
  protected function validator(array $data)
  {
    return Validator::make($data, [
      'name'     => 'required|string|max:255',
      'email'    => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:6|confirmed',
    ]);
  }
  
  public function register(Request $request)
  {
    $this->validator($request->all())->validate();
    
     $user = User::create([
      'name'      => $request['name'],
      'email'     => $request['email'],
      'password'  => bcrypt($request['password']),
      'is_admin'  => 1,
      'is_active' => 1
    ]);
  
    $this->guard()->login($user);
  
    return $this->registered($request, $user)
      ?: redirect($this->redirectPath());
    
  }
}
