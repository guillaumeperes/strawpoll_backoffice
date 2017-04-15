<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DuplicationCheck;
use App\Poll;

class PollsController extends Controller
{
    public function duplicationChecks(Request $request)
    {
        $checks = DuplicationCheck::listAllToArray();
        $headers = array(
            'Content-Type' => 'application/json; charset=utf-8',
        );
        $response = response()->json($checks, 200, $headers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        return $response;
    }

    public function create(Request $request)
    {
        
    }

    public function poll(Request $request)
    {
        $poll = Poll::find($request->poll_id);
        $out = $poll->render();
        $headers = array(
            'Content-Type' => 'application/json; charset=utf-8'
        );
        $response = response()->json($out, 200, $headers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        return $response;
    }
}
