<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Answer;
use \DB;
use \Exception;
use App\Vote;
use App\Poll;

class ResponseController extends Controller
{
    public function respond(Request $request)
    {
        try{
            DB::beginTransaction();

            $poll = Poll::find($request->poll_id);
            $answers = $request->input("answers");         
            if(empty($answers)) {
                throw new Exception('Answers was not found.');
            }
            foreach ($answers as $answerid) {
                $answer = Answer::find($answerid);                
                if(empty($answer)) {
                    throw new Exception('Answer was not found.');                    
                }                
            }
            foreach ($answers as $answer) {
                $vote = new Vote();
                $vote['answers_id'] = $answer;
                $vote['ip'] = '192.168.197.73';
                $vote['cookie'] = '';
                $vote->save();
            }

            DB::commit();
        } catch(Exception $e) {
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
            'message' => "Tout s'est bien déroulé.",
            'data' => array(
                'redirect' => $poll->results()
                )
        );
        $response = response()->json($content, 200, $headers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        return $response;
    }

    public function answers(Request $request)
    {   
        
    }
}
