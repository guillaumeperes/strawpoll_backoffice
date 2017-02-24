<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DuplicationCheck;

class PollsController extends Controller
{
    public function index(Request $request)
    {
    	$checks = DuplicationCheck::orderBy('label', 'ASC')->get();
    	$out = $checks->toArray();
    	return response()->json($out);
    }
}