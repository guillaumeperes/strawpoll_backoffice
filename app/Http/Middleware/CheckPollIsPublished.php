<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Poll;

class CheckPollIsPublished
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
        if ($poll['is_draft'] || empty($poll['published'])) {
            abort(404, __("Le sondage demandé n'existe pas."));
        }
        return $next($request);
    }
}
