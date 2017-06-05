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

    public function renderToArray()
    {
        $out = array();
        $out['id'] = $this['id'];
        $out['question'] = $this['question'];
        $out['multiple_answers'] = $this['multiple_answers'];
        $out['answers'] = array();
        $answers = $this['answers']->sortBy('position');
        foreach ($answers as $answer) {
            $out['answers'][] = $answer->renderToArray();
        }
        
        return $out;
    }

    public function countVotes()
    {
        $count = 0;
        foreach ($this['answers'] as $answer) {
            $count += $answer->countVotes();
        }
        return $count;
    }

    public function resultsVotes()
    {
        $out = array();
        $out['id'] = $this['id'];
        $out['question'] = $this['question'];
        $out['answers'] = array();
        $answers = $this['answers']->sortByDesc(function($answer, $key) {
            return count($answer['votes']);
        });
        foreach ($answers as $answer) {
            $out['answers'][] = $answer->resultsVotes();
        }
        return $out;
    }
}
