<?php

namespace App\Http\Middleware;

use App\Models\Scopes\FrontendEventScope;
use App\Models\Scopes\FrontendEventTypeScope;
use Closure;
use Illuminate\Http\Request;
use Yadda\Enso\Facades\EnsoCrud;

class AddGlobalScopes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        EnsoCrud::modelClass('event')::addGlobalScope(new FrontendEventScope);
        EnsoCrud::modelClass('eventtype')::addGlobalScope(new FrontendEventTypeScope);

        return $next($request);
    }
}
