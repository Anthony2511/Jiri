<?php

namespace Jiri;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    public $timestamps = true;
    protected $fillable = ['name', 'email', 'is_active'];
    
    ##############################################
    # RELATIONS
    ##############################################
    
    public function events()
    {
        return $this->belongsToMany('Event')->withPivot('student_event');
    }
    
    public function implementations()
    {
        return $this->hasMany(Implementation::class);
    }
    
    public function performance()
    {
        return $this->belongsTo(Performance::class, 'id', 'student_id');
    }
}

