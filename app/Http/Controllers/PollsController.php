<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DuplicationCheck;

class PollsController extends Controller
{
    public function index(Request $request)
    {
    	$checks = DuplicationCheck::orderBy('label', 'ASC')->get();
    	$data = $checks->toArray();
    	$response = response()->json($data, 200, array(), JSON_UNESCAPED_UNICODE);
    	$response->header('Content-Type', 'application/json');
    	$response->header('charset', 'utf-8');
    	return $response;
    }
}