<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Poll;

class CheckPollExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $poll = Poll::find($request->poll_id);
        if (empty($poll)) {
            abort(404, __("Le sondage demandé n'existe pas."));
        }
        return $next($request);
    }
}
