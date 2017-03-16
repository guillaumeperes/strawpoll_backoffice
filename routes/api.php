<?php

use Illuminate\Http\Request;
use App\Http\Middleware\CheckPollExists;

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

Route::get('/duplicationchecks', 'PollsController@index')
    ->name('home');

Route::post('/', 'PollsController@create')
    ->name('create');

Route::get('/poll/{poll_id}/', 'PollsController@poll')
    ->where(array('poll_id' => '[1-9][0-9]*'))
    ->middleware(CheckPollExists::class)
    ->name('poll');

Route::post('/poll/{poll_id}/', 'ResponseController@respond')
    ->where(array('poll_id' => '[1-9][0-9]*'))
    ->middleware(CheckPollExists::class)
    ->name('respond');

Route::get('/poll/{poll_id}/r/', 'ResponseController@view')
    ->where(array('poll_id' => '[1-9][0-9]*'))
    ->middleware(CheckPollExists::class)
    ->name('view');
