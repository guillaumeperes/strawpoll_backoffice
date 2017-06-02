<?php

namespace App\DuplicationChecks;

use Illuminate\Http\Request;
use App\DuplicationCheck;
use App\Poll;
use \DB;

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
        $poll = Poll::find($request->poll_id);
        $votes = DB::table('votes')
            ->join('answers', 'votes.answers_id', '=', 'answers.id')
            ->join('questions', 'answers.questions_id', '=', 'questions.id')
            ->join('polls', 'questions.polls_id', '=', 'polls.id')
            ->where('polls.id', '=', $poll['id'])
            ->where('votes.ip', '=', $request->ip())
            ->get();

        return $votes->count() == 0;
    }
}

DuplicationCheck::registerDuplicationCheck(DuplicationCheck_Ip::name(), DuplicationCheck_Ip::classname());
