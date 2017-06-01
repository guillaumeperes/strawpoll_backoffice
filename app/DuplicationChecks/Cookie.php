<?php

namespace App\DuplicationChecks;

use Illuminate\Http\Request;
use App\DuplicationCheck;

class DuplicationCheck_Cookie implements DuplicationCheck_Common
{
    public static function name()
    {
        return 'cookie';
    }

    public static function classname()
    {
        return '\\'.__NAMESPACE__.'\DuplicationCheck_Cookie';
    }

    public static function process(Request $request)
    {
        return true;
    }
}

DuplicationCheck::registerDuplicationCheck(DuplicationCheck_Cookie::name(), DuplicationCheck_Cookie::classname());