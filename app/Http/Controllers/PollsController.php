<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DuplicationCheck;
use App\Poll;
use App\Question;
use App\Answer;
use App\User;
use \DB;
use \Exception;

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
        try {
            DB::beginTransaction();

            // Find duplication check method
            $duplicationCheckId = $request->input('duplication_check');
            $duplicationCheck = DuplicationCheck::find($duplicationCheckId);
            if (empty($duplicationCheck)) {
                throw new Exception('Duplication check was not found.');
            }

            // Find user
            $userId = $request->input('user');
            if (!empty($userId)) {
                $user = User::find($userId);
                if (empty($user)) {
                    throw new Exception("User was not found");
                }
            }

            // Create poll
            $poll = new Poll();
            $poll['duplication_checks_id'] = $duplicationCheck['id'];
            $poll['has_captcha'] = !empty($request->input('has_captcha')) ? true : false;
            $poll['multiple_answers'] = !empty($request->input('multiple_answers')) ? true : false;
            if (!empty($user)) {
                $poll['users_id'] = $user['id'];
                $poll['is_draft'] = !empty($request->input('is_draft')) ? true : false;
            } else {
                $poll['is_draft'] = false;
            }
            if (!$poll['is_draft']) {
                $poll['published'] = date('Y-m-d H:i:s');
            }
            $poll->save();

            // Question
            $vquestion = $request->input('question');
            if (empty($vquestion)) {
                throw new Exception('No question was given');
            }
            $question = new Question();
            $question['polls_id'] = $poll['id'];
            $question['question'] = $vquestion;
            $question->save();

            // Answers
            $vanswers = $request->input('answers');
            if (empty($vanswers) || !is_array($vanswers) || count($vanswers) < 2) {
                throw new Exception('Error while creating the answers');
            }
            foreach ($vanswers as $i => $vanswer) {
                $answer = new Answer();
                $answer['questions_id'] = $question['id'];
                $answer['answer'] = $vanswer;
                $answer['position'] = $i;
                $answer->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            $headers = array('Content-Type' => 'application/json; charset=utf-8');
            $content = array(
                'code' => 500,
                'error' => "Une erreur s'est produite."
            );
            $response = response()->json($content, 500, $headers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return $response;
        }

        $headers = array('Content-Type' => 'application/json; charset=utf-8');
        $content = array(
            'code' => 200,
            'message' => 'Le sondage a été enregistré',
            'data' => array(
                'redirect' => $poll->url()
            )
        );
        $response = response()->json($content, 200, $headers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        return $response;
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
