<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function create()
    {
        return view('onboarding');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_name' => ['required', 'string', 'max:255'],
            'store_name' => ['required', 'string', 'max:255'],
            'gdpr_consent' => ['accepted'],
        ]);

        $team = Team::create(['name' => $validated['team_name']]);

        $team->stores()->create([
            'platform' => 'custom',
            'name' => $validated['store_name'],
            'gdpr_consented_at' => now(),
        ]);

        $request->session()->put('team_id', $team->id);

        return redirect('/');
    }
}
