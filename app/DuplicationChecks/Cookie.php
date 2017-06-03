<?php

namespace App\DuplicationChecks;

use Illuminate\Http\Request;
use App\DuplicationCheck;
use App\Poll;
use \DB;

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
        $poll = Poll::find($request->poll_id);
        $cookie = $request->cookie('strawpoll_cookie');
        if (empty($cookie)) {
            $cookie = $request->input('strawpoll_cookie');
        }
        if (empty($cookie)) {
            $cookie = '';
        }
        $votes = DB::table('votes')
            ->join('answers', 'votes.answers_id', '=', 'answers.id')
            ->join('questions', 'answers.questions_id', '=', 'questions.id')
            ->join('polls', 'questions.polls_id', '=', 'polls.id')
            ->where('polls.id', '=', $poll['id'])
            ->where('votes.cookie', '=', $cookie)
            ->get();

        return $votes->count() == 0;
    }
}

DuplicationCheck::registerDuplicationCheck(DuplicationCheck_Cookie::name(), DuplicationCheck_Cookie::classname());
