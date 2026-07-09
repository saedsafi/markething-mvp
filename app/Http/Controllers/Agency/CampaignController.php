<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\StoreCampaignRequest;
use App\Models\Campaign;
use App\Models\Client;
use App\Models\Persona;
use App\Services\AI\CampaignGenerationService;
use App\Services\AI\PromptTemplateService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function create(Request $request): View
    {
        $clients = $request->user()
            ->clients()
            ->where('status', 'active')
            ->with([
                'personas' => function ($query) {
                    $query->where('status', 'active');
                },
            ])
            ->latest()
            ->get();

        return view('agency.campaigns.create', [
            'clients' => $clients,
        ]);
    }

    public function store(StoreCampaignRequest $request): RedirectResponse
    {
        $user = $request->user();

        $client = Client::query()
            ->where('id', $request->client_id)
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->firstOrFail();

        $persona = Persona::query()
            ->where('id', $request->persona_id)
            ->where('client_id', $client->id)
            ->where('status', 'active')
            ->firstOrFail();

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->startOfDay();

        $durationDays = $startDate->diffInDays($endDate) + 1;

        if ($durationDays > 30) {
            return back()
                ->withErrors([
                    'end_date' => 'Maximum campaign date range is 30 days.',
                ])
                ->withInput();
        }

        $channels = array_values(array_unique($request->channels ?? []));

        $maxPostsAllowed = $durationDays * count($channels);

        if ((int) $request->requested_posts_count > $maxPostsAllowed) {
            return back()
                ->withErrors([
                    'requested_posts_count' =>
                        "Too many materials. Maximum allowed for this date range and channels is {$maxPostsAllowed}.",
                ])
                ->withInput();
        }

        $savedConversionMethods =
            $client->brand_info['conversion_actions'] ?? [];

        $conversionMethods =
            array_values(array_unique($request->conversion_methods ?? []));

        foreach ($conversionMethods as $method) {
            if (! in_array($method, $savedConversionMethods, true)) {
                return back()
                    ->withErrors([
                        'conversion_methods' =>
                            'Please select only conversion methods saved in this client profile.',
                    ])
                    ->withInput();
            }
        }

        $offer = [
            'type' => $request->offer_type,
            'value' => $request->offer_value,
            'conditions' => $request->offer_conditions,
            'deadline' => $request->offer_deadline,
            'code' => $request->offer_code,
        ];

        $promptVersion = app(PromptTemplateService::class)
            ->getActiveMasterPrompt();

        $campaign = Campaign::create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'persona_id' => $persona->id,

            'name' => $request->name,
            'objective' => $request->objective,
            'description' => $request->description,

            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),

            'format_mode' => $request->format_mode,
            'mood' => $request->mood,

            'channels' => $channels,

            'requested_posts_count' =>
                (int) $request->requested_posts_count,

            'status' => 'generating',

            'prompt_version_id' => $promptVersion?->id,

            'snapshot' => [
                'client' => [
                    'id' => $client->id,
                    'name' => $client->name,
                    'industry' => $client->industry,
                    'business_context' => $client->business_context,
                    'business_info' => $client->business_info,
                    'brand_info' => $client->brand_info,
                ],

                'persona' => [
                    'id' => $persona->id,
                    'name' => $persona->name,
                    'age_range' => $persona->age_range,
                    'answers' => $persona->answers,
                ],

                'campaign' => [
                    'name' => $request->name,
                    'topic' => $request->name,
                    'objective' => $request->objective,
                    'description' => $request->description,

                    'offer' => $offer,

                    'conversion_methods' => $conversionMethods,

                    'format_mode' => $request->format_mode,
                    'mood' => $request->mood,

                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),

                    'channels' => $channels,

                    'requested_posts_count' =>
                        (int) $request->requested_posts_count,
                ],
            ],
        ]);

        try {
            app(CampaignGenerationService::class)
                ->generate($campaign);

            return redirect()
                ->route('agency.campaigns.show', $campaign)
                ->with('success', 'Campaign generated successfully.');
        } catch (\Throwable $e) {

            report($e);
        
            return redirect()
                ->route('agency.campaigns.show', $campaign)
                ->withErrors([
                    'generation' => $e->getMessage(),
                ]);
        }
    }

    public function show(Request $request, Campaign $campaign): View
    {
        abort_if($campaign->user_id !== $request->user()->id, 403);

        $campaign->load([
            'posts',
            'client',
            'persona',
        ]);

        return view('agency.campaigns.show', [
            'campaign' => $campaign,
        ]);
    }

    public function index(): View
    {
        $campaigns = Campaign::query()
            ->where('user_id', auth()->id())
            ->with(['client', 'persona'])
            ->withCount('posts')
            ->latest()
            ->paginate(10);

        return view('agency.campaigns.index', [
            'campaigns' => $campaigns,
        ]);
    }
}