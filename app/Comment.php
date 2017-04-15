<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model 
{
    const CREATED_AT = 'published';

    protected $table = 'comments';

    public function user()
    {
        return $this->belongsTo('App\User', 'users_id');
    }

    public function poll()
    {
        return $this->belongsTo('App\Poll', 'polls_id');
    }
}
