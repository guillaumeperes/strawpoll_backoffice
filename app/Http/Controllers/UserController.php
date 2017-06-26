<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\RegisterException;
use App\Exceptions\LoginException;
use App\User;
use Firebase\JWT\JWT;
use \Exception;
use \DB;
use \Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        try {
            DB::beginTransaction();

            $email = $request->input('email');
            if (empty($email)) {
                throw new LoginException("Vous n'avez pas renseigné d'identifiant");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new LoginException("L'identifiant entré ne correspond pas à une adresse e-mail");
            }
            $user = User::where('email', '=', $email)->first();
            if (empty($user)) {
                throw new LoginException("L'identifiant entré ne correspond à aucun utilisateur connu");
            }
            $password = $request->input('password');
            if (empty($password) || !Hash::check($password, $user['password'])) {
                throw new LoginException('Le mot de passe spécifié est incorrect');
            }
            // On a un utilisateur avec le bon email et mot de passe
            $token = array(
                'id' => $user['id'],
                'iss' => url('/'),
                'iat' => time(),
                'nbf' => time(),
                'exp' => strtotime('next day')
            );
            $jwt = JWT::encode($token, env('APP_KEY'));

            $user['last_login'] = time();
            $user->save();

        } catch (LoginException $e) {
            DB::rollback();
            $headers = array('Content-Type' => 'application/json; charset=utf-8');
            $content = array(
                'status' => 500,
                'error' => $e->getMessage(),
            );
            $response = response()->json($content, 500, $headers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return $response;
        } catch (Exception $e) {
            DB::rollback();
            $headers = array('Content-Type' => 'application/json; charset=utf-8');
            $content = array(
                'status' => 500,
                'error' => "Une erreur s'est produite",
            );
            $response = response()->json($content, 500, $headers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return $response;
        }
        DB::commit();
        $headers = array('Content-Type' => 'application/json; charset=utf-8');
        $content = array(
            'status' => 200,
            'message' => '',
            'data' => array(
                'token' => $jwt
            )
        );
        $response = response()->json($content, 200, $headers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        return $response;
    }

    public function register(Request $request) 
    {
        try {
            DB::beginTransaction();

            // Email
            $email = $request->input('email');
            if (empty($email) || !strlen($email)) {
                throw new RegisterException("Aucune adresse email n'a été renseignée");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new RegisterException("Le format de l'adresse email renseignée est incorrect");
            }
            $user = User::where('email', '=', $email)->first();
            if (!empty($user)) {
                throw new RegisterException('Un compte utilisateur existe déjà pour cette adresse e-mail');
            }

            // Mot de passe
            $password = $request->input('password');
            if (empty($password) || !strlen($password)) {
                throw new RegisterException("Aucun mot de passe n'a été renseigné");
            }
            $confirmation = $request->input('confirmation');
            if (empty($confirmation) || !strlen($confirmation)) {
                throw new RegisterException("La confirmation du mot de passe n'a pas été renseignée");
            }
            if ($password !== $confirmation) {
                throw new RegisterException('Le mot de passe et sa confirmation doivent être identiques');
            }

            // Création du user
            $user = new User();
            $user['email'] = $email;
            $user['password'] = bcrypt($password);
            $user['last_login'] = time();
            $user->save();
            
        } catch (RegisterException $e) {
            DB::rollback();
            $headers = array('Content-Type' => 'application/json; charset=utf-8');
            $content = array(
                'status' => 500,
                'error' => $e->getMessage(),
            );
            $response = response()->json($content, 500, $headers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return $response;
        } catch (Exception $e) {
            DB::rollback();
            $headers = array('Content-Type' => 'application/json; charset=utf-8');
            $content = array(
                'status' => 500,
                'error' => "Une erreur s'est produite"
            );
            $response = response()->json($content, 500, $headers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return $response;
        }
        DB::commit();
        $user->refresh();
        $token = array(
            'id' => $user['id'],
            'iss' => url('/'),
            'iat' => time(),
            'nbf' => time(),
            'exp' => strtotime('next day')
        );
        $jwt = JWT::encode($token, env('APP_KEY'));
        $headers = array('Content-Type' => 'application/json; charset=utf-8');
        $content = array(
            'status' => 200,
            'message' => "L'utilisateur a bien été créé",
            'data' => array(
                'token' => $jwt
            )
        );
        $response = response()->json($content, 200, $headers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        return $response;
    }

    public function infos(Request $request)
    {
        $token = (array) JWT::decode($request->access_token, env('APP_KEY'), array('HS256'));
        $user = User::find($token['id']);
        $headers = array('Content-Type' => 'application/json; charset=utf-8');
        $content = array(
            'status' => 200,
            'message' => '',
            'data' => array(
                'user' => array(
                    'email' => $user['email'],
                    'created' => !empty($user['created']) ? date('d/m/Y - H:i', $user['created']) : null,
                    'updated' => !empty($user['updated']) ? date('d/m/Y - H:i', $user['updated']) : null,
                    'last_login' => !empty($user['last_login']) ? date('d/m/Y - H:i', $user['last_login']) : null
                )
            )
        );
        $response = response()->json($content, 200, $headers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        return $response;
    }
}
