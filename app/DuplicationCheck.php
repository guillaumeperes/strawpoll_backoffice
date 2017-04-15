<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DuplicationCheck extends Model
{
    public $timestamps = false;

    protected $table = 'duplication_checks';

    public function polls()
    {
        return $this->hasMany('App\Polls', 'duplication_checks_id');
    }

    public static function listAllToArray()
    {
        $checks = self::orderBy('label', 'ASC')->get();
        $checksArr = $checks->toArray();
        $data = array('duplication_checks' => $checksArr);
        return $data;
    }
}
