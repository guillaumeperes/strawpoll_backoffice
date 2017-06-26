<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
	const CREATED_AT = 'created';
	
    protected $table = 'votes';

    public function answer()
    {
        return $this->belongsTo('App\Answer', 'answers_id');
    }
}
