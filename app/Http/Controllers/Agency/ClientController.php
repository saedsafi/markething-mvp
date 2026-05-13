<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\StoreClientRequest;
use App\Http\Requests\Agency\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $clients = $request->user()
            ->clients()
            ->withCount(['personas', 'campaigns'])
            ->latest()
            ->get();

        return view('agency.clients.index', [
            'clients' => $clients,
            'activeClients' => $clients->where('status', 'active')->count(),
            'inactiveClients' => $clients->where('status', 'inactive')->count(),
            'totalPersonas' => $clients->sum('personas_count'),
        ]);
    }

    public function create(): View
    {
        return view('agency.clients.create');
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        $user = $request->user();

        $activeClientCount = $user->clients()
            ->where('status', 'active')
            ->count();

        if ($activeClientCount >= $user->client_limit) {
            return back()
                ->withErrors([
                    'client_limit' => 'You have reached your client profile limit.',
                ])
                ->withInput();
        }

        $client = $user->clients()->create([
            'name' => $request->name,
            'industry' => $request->industry,
            'business_context' => $request->business_context,
            'business_info' => [
                'business_offer' => $request->business_offer,
            ],
            'brand_info' => [
                'brand_voice' => $request->brand_voice,
                'brand_personality' => $request->brand_personality,
                'brand_values' => $request->brand_values,
            ],
            'status' => 'active',
        ]);

        if ($request->filled('persona_name')) {
            $client->personas()->create([
                'name' => $request->persona_name,
                'age_range' => $request->persona_age_range,
                'answers' => [
                    'description' => $request->persona_description,
                    'channel' => 'Instagram',
                ],
                'status' => 'active',
            ]);
        }

        return redirect()
            ->route('agency.clients.show', $client)
            ->with('success', 'Client profile created successfully.');
    }

    public function show(Request $request, Client $client): View
    {
        $this->authorizeClient($request, $client);

        $client->load(['personas', 'campaigns']);

        return view('agency.clients.show', [
            'client' => $client,
        ]);
    }

    public function edit(Request $request, Client $client): View
    {
        $this->authorizeClient($request, $client);

        $client->load('personas');

        return view('agency.clients.create', [
            'client' => $client,
            'isEditing' => true,
        ]);
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $this->authorizeClient($request, $client);

        $client->update([
            'name' => $request->name,
            'industry' => $request->industry,
            'business_context' => $request->business_context,
            'business_info' => [
                'business_offer' => $request->business_offer,
            ],
            'brand_info' => [
                'brand_voice' => $request->brand_voice,
                'brand_personality' => $request->brand_personality,
                'brand_values' => $request->brand_values,
            ],
        ]);

        return redirect()
            ->route('agency.clients.show', $client)
            ->with('success', 'Client profile updated successfully.');
    }

    public function deactivate(Request $request, Client $client): RedirectResponse
    {
        $this->authorizeClient($request, $client);

        $client->update([
            'status' => 'inactive',
        ]);

        return back()->with('success', 'Client profile deactivated successfully.');
    }

    public function reactivate(Request $request, Client $client): RedirectResponse
    {
        $this->authorizeClient($request, $client);

        $user = $request->user();

        $activeClientCount = $user->clients()
            ->where('status', 'active')
            ->count();

        if ($activeClientCount >= $user->client_limit) {
            return back()->withErrors([
                'client_limit' => 'You have reached your client profile limit.',
            ]);
        }

        $client->update([
            'status' => 'active',
        ]);

        return back()->with('success', 'Client profile reactivated successfully.');
    }

    private function authorizeClient(Request $request, Client $client): void
    {
        abort_if($client->user_id !== $request->user()->id, 403);
    }
}