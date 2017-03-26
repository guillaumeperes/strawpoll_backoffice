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
        $headers = array(
            'Content-Type' => 'application/json; charset=utf-8',
        );
        $response = response()->json($data, 200, $headers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        return $response;
    }

    public function create(Request $request)
    {
        
    }

    public function poll(Request $request)
    {

    }
}
