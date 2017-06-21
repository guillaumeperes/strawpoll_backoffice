<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Colors\RandomColor;

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

    public function renderToArray()
    {
        $out = array();
        $out['id'] = $this['id'];
        $out['answer'] = $this['answer'];
        return $out;
    }

    public function countVotes()
    {
        return count($this['votes']);
    }

    public function resultsVotes()
    {
        $out = array();
        $out['id'] = $this['id'];
        $out['answer'] = $this['answer'];
        $out['votes'] = $this->countVotes();
        $out['color'] = $this['color'];
        return $out;
    }

    public function assignColor()
    {
        $color = RandomColor::one();
        $free = false;
        while( $free === false) {
            $count = self::where('color', '=', $color)->where('questions_id', '=', $this['questions_id'])->count();
            if($count == 0){
                $free = true;
            } else{
                $color = RandomColor::one();
            }
        }
        return $color;
    }
}
