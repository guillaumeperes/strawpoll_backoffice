<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    public $timestamps = false;

    protected $table = 'votes';

    public function poll()
    {
        return $this->belongsTo('App\Poll', 'polls_id');
    }

    public function answer()
    {
        return $this->belongsTo('App\Answer', 'answers_id');
    }
}
