<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    const CREATED_AT = 'created';

    const UPDATED_AT = 'updated';

    protected $dates = array(
        'published'
    );

    protected $table = 'polls';

    public function duplicationCheck()
    {
        return $this->belongsTo('App\DuplicationCheck', 'duplication_checks_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'users_id');
    }

    public function questions()
    {
        return $this->hasMany('App\Question', 'polls_id'); // permet plusieurs questions par sondage (peut être utile dans l'avenir)
    }

    public function comments()
    {
        return $this->hasMany('App\Comment', 'polls_id');
    }

    public function render()
    {
        $out = array();
        $out['id'] = $this['id'];
        $out['has_captcha'] = $this['has_captcha'];
        $out['multiple_answers'] = $this['multiple_answers'];
        $out['is_draft'] = $this['is_draft'];
        $out['created'] = !empty($this['created']) ? $this['created']->timestamp : null;
        $out['updated'] = !empty($this['updated']) ? $this['updated']->timestamp : null;
        $out['published'] = !empty($this['published']) ? $this['published']->timestamp : null;

        $duplicationCheck = $this['duplicationCheck'];
        $out['duplication_check'] = !empty($duplicationCheck) ? $duplicationCheck->toArray() : array();

        $out['questions'] = array();
        $questions = $this['questions']->sortBy('position');
        foreach ($questions as $question) {
            $out['questions'][] = $question->render();
        }

        $out['comments'] = array();
        $comments = $this['comments']->sortBy('published');
        foreach ($comments as $comment) {
            $out['comments'][] = $comment->render();
        }

        return $out;
    }

    public function url()
    {
        return route('poll', array('poll_id' => $this['id']));
    }
    public function results()
    {
        return route('answers', array('poll_id' => $this['id']));
    }
}
