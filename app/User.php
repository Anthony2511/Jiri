<?php

namespace Jiri;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company',
        'is_admin',
        'is_active'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    public static function generatePassword()
    {
        return bcrypt(str_random(35));
    }
    
    public static function sendWelcomeEmail($user)
    {
        // Generate a new reset password token
        $token = app('auth.password.broker')->createToken($user);
        
        // Send Mail
        Mail::send('emails.welcome', ['user' => $user, 'token' => $token], function ($m) use ($user) {
           $m->from(env('EMAIL_APP'), 'Jiri');
           
           $m->to($user->email, $user->name)->subject('Bienvenue sur Jiri');
        });
    }
    
    // Relations
    public function events()
    {
        return $this->belongsToMany(Event::class, 'user_event');
    }
    
}
