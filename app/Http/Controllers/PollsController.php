<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DuplicationCheck;

class PollsController extends Controller
{
    public function duplicationChecks(Request $request)
    {
        $checks = DuplicationCheck::orderBy('label', 'ASC')->get();
        $checksArr = $checks->toArray();
        $data = array('duplication_checks' => $checksArr);
        $response = response()->json($data, 200, array(), JSON_UNESCAPED_UNICODE);
        return $response;
    }

    public function create(Request $request)
    {
        
    }

    public function poll(Request $request)
    {

    }
}
