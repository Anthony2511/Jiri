<?php

namespace Jiri;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';
    public $timestamps = true;
    protected $fillable = ['course_name', 'academic_year', 'exam_session', 'status', 'date_event'];
    
    ##############################################
    # RELATIONS
    ##############################################
    
    public function students()
    {
        return $this->belongsToMany('Jiri\Student', 'student_event')->withTimestamps();
    }
    
    public function users()
    {
        return $this->belongsToMany('Jiri\User', 'user_event')->withTimestamps();
    }
    
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    
    public function implementations()
    {
        return $this->hasMany(Implementation::class);
    }
}
