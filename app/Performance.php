<?php

namespace Jiri;

use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
  protected $table = 'performances';
  public $timestamps = true;
  
  protected $fillable = [
    'calculated_score',
    'manual_score',
    'description',
    'student_id',
    'event_id'
  ];
}
