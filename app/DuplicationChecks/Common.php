<?php

namespace App\DuplicationChecks;

use Illuminate\Http\Request;

interface DuplicationCheck_Common 
{
    public static function name();

    public static function classname();

    public static function process(Request $request);
}
