<?php

namespace Jiri;

use Illuminate\Database\Eloquent\Model;
use Jiri\Score;

class Implementation extends Model
{
    protected $table = 'implementations';
    public $timestamps = true;
    
    protected $fillable = [
        'project_id',
        'student_id',
        'event_id',
        'url_project',
        'url_repo',
        'ponderation',
        'isEvaluated'
    ];
    protected $appends = ['avg_score'];
  
    ## MUTATORS
    public function getAvgScoreAttribute()
    {
      return '';
    }
    
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
    public function student()
    {
        return $this->belongsTo('Jiri\Student');
    }
    
    public function event()
    {
        return $this->belongsTo('Jiri\Event');
    }
    
    public function score()
    {
        return $this->belongsTo(Score::class, 'id', 'implementation_id');
    }
    
    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
