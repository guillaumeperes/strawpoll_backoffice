<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model 
{
    const CREATED_AT = 'created';

    const UPDATED_AT = 'updated';

    protected $table = 'users';

    public function polls()
    {
        return $this->hasMany('App\Poll', 'users_id');
    }
}
