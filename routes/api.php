<?php

use Illuminate\Http\Request;
use App\Http\Middleware\CheckPollExists;
use App\Http\Middleware\CheckPollIsPublished;
use App\Http\Middleware\CheckUserTokenIsValid;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/duplicationchecks/', 'PollsController@duplicationChecks')
    ->name('duplicationchecks');

Route::post('/poll/', 'PollsController@create')
    ->name('create');

Route::get('/poll/{poll_id}/', 'PollsController@poll')
    ->where(array('poll_id' => '[1-9][0-9]*'))
    ->middleware(CheckPollExists::class)
    ->middleware(CheckPollIsPublished::class)
    ->name('poll');

Route::post('/poll/{poll_id}/answers/', 'ResponseController@respond')
    ->where(array('poll_id' => '[1-9][0-9]*'))
    ->middleware(CheckPollExists::class)
    ->name('respond');

Route::get('/poll/{poll_id}/results/', 'ResponseController@results')
    ->where(array('poll_id' => '[1-9][0-9]*'))
    ->middleware(CheckPollExists::class)
    ->name('answers');

Route::get('/poll/{poll_id}/results/channel/', 'ResponseController@channel')
    ->where(array('poll_id' => '[1-9][0-9]*'))
    ->middleware(CheckPollExists::class)
    ->middleware(CheckPollIsPublished::class)
    ->name('channel');

Route::post('/register/', 'UserController@register')
    ->name('register');

Route::post('/login/', 'UserController@login')
    ->name('login');

Route::get('/user/{access_token}/infos/', 'UserController@infos')
    ->middleware(CheckUserTokenIsValid::class)
    ->name('userInfos');
