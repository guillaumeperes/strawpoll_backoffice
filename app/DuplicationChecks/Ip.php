<?php

namespace App\DuplicationChecks;

use Illuminate\Http\Request;
use App\DuplicationCheck;

class DuplicationCheck_Ip implements DuplicationCheck_Common
{
    public static function name()
    {
        return 'ip';
    }

    public static function classname()
    {
        return '\\'.__NAMESPACE__.'\DuplicationCheck_Ip';
    }

    public static function process(Request $request)
    {
        return true;
    }
}

DuplicationCheck::registerDuplicationCheck(DuplicationCheck_Ip::name(), DuplicationCheck_Ip::classname());
