<?php

namespace App\Http\Middleware;

use App\Models\Team;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetTenant
{
    public function handle(Request $request, Closure $next)
    {
        if ($teamId = $request->session()->get('team_id')) {
            $team = Team::find($teamId);
            if ($team) {
                App::instance('tenant', $team);
            }
        }

        return $next($request);
    }
}
