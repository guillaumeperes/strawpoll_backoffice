<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    const CREATED_AT = 'created';

    const UPDATED_AT = 'updated';

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
        return $this->hasMany('App\Question', 'polls_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment', 'polls_id');
    }

}
