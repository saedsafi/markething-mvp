<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\StorePersonaRequest;
use App\Http\Requests\Agency\UpdatePersonaRequest;
use App\Models\Client;
use App\Models\Persona;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    public function store(StorePersonaRequest $request, Client $client): RedirectResponse
    {
        $this->authorizeClient($request, $client);

        $activePersonaCount = $client->personas()
            ->where('status', 'active')
            ->count();

        if ($activePersonaCount >= 5) {
            return back()->withErrors([
                'persona_limit' => 'Each client can have up to 5 active personas.',
            ]);
        }

        $client->personas()->create([
            'name' => $request->name,
            'age_range' => $request->age_range,
            'answers' => [
                'description' => $request->description,
                'channel' => $request->channel,
            ],
            'status' => 'active',
        ]);

        return back()->with('success', 'Persona added successfully.');
    }

    public function update(UpdatePersonaRequest $request, Persona $persona): RedirectResponse
    {
        $this->authorizePersona($request, $persona);

        $persona->update([
            'name' => $request->name,
            'age_range' => $request->age_range,
            'answers' => [
                'description' => $request->description,
                'channel' => $request->channel,
            ],
        ]);

        return back()->with('success', 'Persona updated successfully.');
    }

    public function deactivate(Request $request, Persona $persona): RedirectResponse
    {
        $this->authorizePersona($request, $persona);

        $persona->update([
            'status' => 'inactive',
        ]);

        return back()->with('success', 'Persona deactivated successfully.');
    }

    public function reactivate(Request $request, Persona $persona): RedirectResponse
    {
        $this->authorizePersona($request, $persona);

        $client = $persona->client;

        $activePersonaCount = $client->personas()
            ->where('status', 'active')
            ->count();

        if ($activePersonaCount >= 5) {
            return back()->withErrors([
                'persona_limit' => 'Each client can have up to 5 active personas.',
            ]);
        }

        $persona->update([
            'status' => 'active',
        ]);

        return back()->with('success', 'Persona reactivated successfully.');
    }

    private function authorizeClient(Request $request, Client $client): void
    {
        abort_if($client->user_id !== $request->user()->id, 403);
    }

    private function authorizePersona(Request $request, Persona $persona): void
    {
        abort_if($persona->client->user_id !== $request->user()->id, 403);
    }
}