<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Poll;
use App\DuplicationCheck;
use App\Question;
use App\Answer;
use App\Vote;
use \DB;
use App\Exceptions\RespondPollException;
use \Exception;

class ResponseController extends Controller
{
    public function respond(Request $request)
    {
        try{
            DB::beginTransaction();

            $poll = Poll::find($request->poll_id);
            $duplicationCheck = $poll['duplicationCheck'];
            if (empty($duplicationCheck)) {
                throw new Exception('Duplication check was not found');
            }
            if (!$duplicationCheck->isVoteAllowed($poll)) {
                throw new RespondPollException('Vous ne pouvez plus voter à ce sondage');
            }
            $answers = $request->input('answers');
            if (empty($answers)) {
                throw new Exception('Answers were not found');
            }
            $questions = array();
            foreach ($answers as $answerId) {
                $answer = Answer::find($answerId);
                if (empty($answer)) {
                    throw new Exception('Answer was not found');
                }
                $question = $answer['question'];
                if (empty($question)) {
                    throw new Exception('Question was not found');
                }
                $questions[$question['id']][] = $answer['id'];
            }
            $pollQuestions = $poll['questions'];
            foreach ($pollQuestions as $pollQuestion) {
                if (empty($questions[$pollQuestion['id']])) {
                    throw new RespondPollException('Vous devez répondre à toutes les questions du sondage');
                }
                if (!$pollQuestion['multiple_answers'] && count($questions[$pollQuestion['id']]) > 1) {
                    throw new RespondPollException('Certaines questions doivent comporter une unique réponse'); // TODO indiquer quelle question
                }
                foreach ($questions[$pollQuestion['id']] as $answerId) {
                    $vote = new Vote();
                    $vote['answers_id'] = $answerId;
                    $vote['ip'] = $request->ip();
                    $vote['cookie'] = '';
                    $vote->save();
                }
            }
        } catch (RespondPollException $e) {
            DB::rollback();
            $headers = array('Content-Type' => 'application/json; charset=utf-8');
            $content = array(
                'code' => 500,
                'error' => $e->getMessage()
            );
            $response = response()->json($content, 500, $headers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return $response;
        } catch(Exception $e) {
            DB::rollback();
            $headers = array('Content-Type' => 'application/json; charset=utf-8');
            $content = array(
                'code' => 500,
                'error' => "Une erreur s'est produite"
            );
            $response = response()->json($content, 500, $headers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return $response;            
        }
        DB::commit();  
        $headers = array('Content-Type' => 'application/json; charset=utf-8');
        $content = array(
            'code' => 200,
            'message' => "Votre vote a été sauvegardé avec succès",
            'data' => array(
                'poll_id' => $poll['id'],
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
