<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model 
{
    const CREATED_AT = 'published';

    const UPDATED_AT = null;

    protected $table = 'comments';

    public function user()
    {
        return $this->belongsTo('App\User', 'users_id');
    }

    public function poll()
    {
        return $this->belongsTo('App\Poll', 'polls_id');
    }

    public function renderToArray()
    {
        $out = array();
        $out['id'] = $this['id'];
        $out['user'] = !empty($this['user']) ? $this['user']['username'] : null;
        $out['comment'] = $this['comment'];
        $out['published'] = !empty($this['published']) ? $this['published']->timestamp : null;
        
        return $out;
    }
}
