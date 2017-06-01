<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class DuplicationCheck extends Model
{
    protected static $duplicationChecks = array();

    public $timestamps = false;

    protected $table = 'duplication_checks';

    public static function registerDuplicationCheck($name, $classname)
    {
        self::$duplicationChecks[$name] = $classname;
    }

    public function isVoteAllowed(Poll $poll)
    {
        $request = request();
        if (array_key_exists($this['name'], self::$duplicationChecks)) {
            $classname = self::$duplicationChecks[$this['name']];
            if (method_exists($classname, 'process')) {
                return $classname::process($request);
            }
        }
        return false;
    }

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

require_once __DIR__.'/DuplicationChecks/Common.php';
require_once __DIR__.'/DuplicationChecks/Ip.php';
require_once __DIR__.'/DuplicationChecks/Cookie.php';
require_once __DIR__.'/DuplicationChecks/NotControlled.php';
