<?php

namespace Jiri;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $table = 'scores';
    public $timestamps = true;
    protected $fillable = ['score', 'comment'];
  
    ## RELATIONS
    public function implementation()
    {
        return $this->belongsTo('Implementations');
    }
}
