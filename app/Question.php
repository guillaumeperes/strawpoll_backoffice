<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public $timestamps = false;

    protected $table = 'questions';

    public function poll() 
    {
        return $this->belongsTo('App\Poll', 'polls_id');
    }

    public function answers()
    {
    	return $this->hasMany('App\Answer', 'questions_id');
    }

    public function render()
    {
        $out = array();
        $out['id'] = $this['id'];
        $out['question'] = $this['question'];
        $out['answers'] = array();
        $answers = $this['answers']->sortBy('position');
        foreach ($answers as $answer) {
            $out['answers'][] = $answer->render();
        }
        
        return $out;
    }
}
