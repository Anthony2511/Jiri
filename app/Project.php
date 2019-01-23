<?php

namespace Jiri;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';
    public $timestamps = true;
    
    protected $fillable = [
        'name',
        'name_short',
        'description',
        'ponderation_default',
        'event_id'
    ];
    
    public function implementations()
    {
        return $this->hasMany('Jiri\Implementation');
    }
    
    public function events()
    {
        return $this->belongsToMany('Jiri\Implementation');
    }
}
