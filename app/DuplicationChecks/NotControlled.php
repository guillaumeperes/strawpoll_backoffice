<?php

namespace App\DuplicationChecks;

use Illuminate\Http\Request;
use App\DuplicationCheck;

class DuplicationCheck_NotControlled implements DuplicationCheck_Common
{
    public static function name()
    {
        return 'notcontrolled';
    }

    public static function classname()
    {
        return '\\'.__NAMESPACE__.'\DuplicationCheck_NotControlled';
    }

    public static function process(Request $request)
    {
        return true;
    }
}

DuplicationCheck::registerDuplicationCheck(DuplicationCheck_NotControlled::name(), DuplicationCheck_NotControlled::classname());
