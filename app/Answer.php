<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public $timestamps = false;

    protected $table = 'answers';

    public function question()
    {
        return $this->belongsTo('App\Question', 'questions_id');
    }

    public function votes()
    {
        return $this->hasMany('App\Vote', 'answers_id');
    }
}
