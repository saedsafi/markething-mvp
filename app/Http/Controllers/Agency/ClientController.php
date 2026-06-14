<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\StoreClientRequest;
use App\Http\Requests\Agency\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

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
                'industry' => $request->industry,
                'industry_other' => $request->industry_other,
                'business_type' => $request->business_type,
                'business_type_other' => $request->business_type_other,
                'country' => $request->country,
                'city' => $request->city ?? [],
                'price_tier' => $request->price_tier,
                'differentiator' => $request->differentiator,
                'brand_positioning' => $request->brand_positioning ?? [],
                'brand_avoids' => $request->brand_avoids ?? [],
                'brand_avoids_other' => $request->brand_avoids_other,
                'business_age' => $request->business_age,
            ],

            'brand_info' => [
                'arabic_dialect' => $request->arabic_dialect,
                'emoji_usage' => $request->emoji_usage,
                'english_usage' => $request->english_usage,
                'words_to_avoid' => $request->words_to_avoid,
                'caption_samples' => $request->caption_samples,
                'conversion_actions' => $request->conversion_actions ?? [],
                'conversion' => [
                    'location' => $request->conversion_location,
                    'whatsapp' => $request->conversion_whatsapp,
                    'phone' => $request->conversion_phone,
                    'delivery_app' => $request->conversion_delivery_app,
                    'website' => $request->conversion_website,
                    'booking' => $request->conversion_booking,
                    'social_dm' => $request->conversion_social_dm,
                    'signup' => $request->conversion_signup,
                ],
            ],

            'status' => 'active',
        ]);

        $client->personas()->create([
            'name' => $request->persona_name,
            'age_range' => $request->persona_age_range,
            'answers' => [
                'gender' => $request->persona_gender,
                'who' => $request->persona_who,
                'buyer_is_user' => $request->persona_buyer_is_user,
                'decider' => $request->persona_decider,
                'priorities' => $request->persona_priorities ?? [],
                'objection' => $request->persona_objection,
            ],
            'status' => 'active',
        ]);

        return redirect()
            ->route('agency.clients.show', $client)
            ->with('success', 'Client profile created successfully.');
    }

    public function show(Request $request, Client $client): View
    {
        $this->authorizeClient($request, $client);

        $client->load(['personas', 'campaigns']);

        $personaLimit = app(\App\Services\AppSettingService::class)
        ->int('max_personas_per_client', 5);
    
    return view('agency.clients.show', [
        'client' => $client,
        'personaLimit' => $personaLimit,
        'aiDisabled' => blank($client->business_context),
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
                'industry' => $request->industry,
                'industry_other' => $request->industry_other,
                'business_type' => $request->business_type,
                'business_type_other' => $request->business_type_other,
                'country' => $request->country,
                'city' => $request->city ?? [],
                'price_tier' => $request->price_tier,
                'differentiator' => $request->differentiator,
                'brand_positioning' => $request->brand_positioning ?? [],
                'brand_avoids' => $request->brand_avoids ?? [],
                'brand_avoids_other' => $request->brand_avoids_other,
                'business_age' => $request->business_age,
            ],

            'brand_info' => [
                'arabic_dialect' => $request->arabic_dialect,
                'emoji_usage' => $request->emoji_usage,
                'english_usage' => $request->english_usage,
                'words_to_avoid' => $request->words_to_avoid,
                'caption_samples' => $request->caption_samples,
                'conversion_actions' => $request->conversion_actions ?? [],
                'conversion' => [
                    'location' => $request->conversion_location,
                    'whatsapp' => $request->conversion_whatsapp,
                    'phone' => $request->conversion_phone,
                    'delivery_app' => $request->conversion_delivery_app,
                    'website' => $request->conversion_website,
                    'booking' => $request->conversion_booking,
                    'social_dm' => $request->conversion_social_dm,
                    'signup' => $request->conversion_signup,
                ],
            ],
        ]);

        $persona = $client->personas()->first();

        if ($persona && $request->filled('persona_name')) {
            $persona->update([
                'name' => $request->persona_name,
                'age_range' => $request->persona_age_range,
                'answers' => [
                    'gender' => $request->persona_gender,
                    'who' => $request->persona_who,
                    'buyer_is_user' => $request->persona_buyer_is_user,
                    'decider' => $request->persona_decider,
                    'priorities' => $request->persona_priorities ?? [],
                    'objection' => $request->persona_objection,
                ],
            ]);
        }

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

    public function destroy(Request $request, Client $client): RedirectResponse
{
    $this->authorizeClient($request, $client);

    DB::transaction(function () use ($client) {
        $client->personas()->delete();
        $client->delete();
    });

    return redirect()
        ->route('agency.clients.index')
        ->with('success', 'Client profile deleted successfully.');
}
}