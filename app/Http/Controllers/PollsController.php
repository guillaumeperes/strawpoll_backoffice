<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\CreatePollException;
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
        $headers = array('Content-Type' => 'application/json; charset=utf-8');
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
                throw new CreatePollException("Aucune méthode de contrôle des votes multiples n'a été renseignée.");
            }

            // Find user
            $userId = $request->input('user');
            if (!empty($userId)) {
                $user = User::find($userId);
                if (empty($user)) {
                    throw new CreatePollException("L'utilisateur renseigné n'existe pas.");
                }
            }

            // Create poll
            $poll = new Poll();
            $poll['duplication_checks_id'] = $duplicationCheck['id'];
            $poll['has_captcha'] = !empty($request->input('has_captcha')) ? true : false;
            if (!empty($user)) {
                $poll['users_id'] = $user['id'];
                $poll['is_draft'] = !empty($request->input('is_draft')) ? true : false;
            } else {
                $poll['is_draft'] = false;
            }
            if (!$poll['is_draft']) {
                $poll['published'] = time();
            }
            $poll->save();

            // Questions and answers
            $vquestions = $request->input('questions');
            if (empty($vquestions) || !is_array($vquestions)) {
                throw new CreatePollException("Erreur de format des questions.");
            }
            $filteredQuestions = array();
            foreach ($vquestions as $vquestion) {
                // Test du tableau
                if (!is_array($vquestion)) {
                    continue;
                }
                // Test de la question
                if (!isset($vquestion['question']) || !is_string($vquestion['question']) || strlen(trim($vquestion['question'])) == 0) {
                    continue;
                }
                // Test du tableau de réponses
                if (empty($vquestion['answers']) || !is_array($vquestion['answers'])) {
                    continue;
                }
                $filteredQuestions[] = $vquestion;
            }
            if (count($filteredQuestions) < 1) {
                throw new CreatePollException("Veuillez renseigner au moins une question.");
            }
            foreach ($filteredQuestions as $i => $filteredQuestion) {
                $question = new Question();
                $question['polls_id'] = $poll['id'];
                $question['question'] = trim($filteredQuestion['question']);
                $question['multiple_answers'] = !empty($filteredQuestion['multiple_answers']) ? true : false;
                $question['position'] = $i;
                $question->save();

                // Réponses
                $vanswers = $filteredQuestion['answers'];
                $filteredAnswers = array();
                foreach ($vanswers as $vanswer) {
                    if (strlen(trim($vanswer)) > 0) {
                        $filteredAnswers[] = $vanswer;
                    }
                }
                if (count($filteredAnswers) < 2) {
                    throw new CreatePollException("Veuillez renseigner au minimum deux réponses par question.");
                }
                foreach ($filteredAnswers as $j => $filteredAnswer) {
                    $answer = new Answer();
                    $answer['questions_id'] = $question['id'];
                    $answer['answer'] = $filteredAnswer;
                    $answer['position'] = $j;
                    $answer['color'] = $answer->assignColor();
                    $answer->save();
                }
            }
        } catch (CreatePollException $e) {
            DB::rollback();
            $headers = array('Content-Type' => 'application/json; charset=utf-8');
            $content = array(
                'code' => 500,
                'error' => $e->getMessage()
            );
            $response = response()->json($content, 500, $headers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return $response;
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

        DB::commit();
        $headers = array('Content-Type' => 'application/json; charset=utf-8');
        $content = array(
            'code' => 200,
            'message' => 'Le sondage a été enregistré',
            'data' => array(
                'poll_id' => $poll['id'],
                'redirect' => $poll->url()
            )
        );
        $response = response()->json($content, 200, $headers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        return $response;
    }

    public function poll(Request $request)
    {
        $poll = Poll::find($request->poll_id);
        $out = $poll->renderToArray();
        $headers = array('Content-Type' => 'application/json; charset=utf-8');
        $response = response()->json($out, 200, $headers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        return $response;
    }
}
