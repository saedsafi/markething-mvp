<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\StoreCampaignRequest;
use App\Models\Campaign;
use App\Models\Client;
use App\Models\Persona;
use App\Models\PromptTemplate;
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

        if ($durationDays > 90) {
            return back()
                ->withErrors([
                    'end_date' => 'Maximum campaign date range is 90 days.',
                ])
                ->withInput();
        }

        $channels = array_values(array_unique($request->channels));

        $maxPostsAllowed = $durationDays * count($channels);

        if ((int) $request->requested_posts_count > $maxPostsAllowed) {
            return back()
                ->withErrors([
                    'requested_posts_count' => "Too many posts. Maximum allowed for this date range and channels is {$maxPostsAllowed}.",
                ])
                ->withInput();
        }

        $promptVersion = PromptTemplate::query()
            ->where('type', 'master')
            ->where('is_active', true)
            ->with('currentVersion')
            ->first()
            ?->currentVersion;

        $campaign = Campaign::create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'persona_id' => $persona->id,
            'name' => $request->name,
            'objective' => $request->objective,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'channels' => $channels,
            'requested_posts_count' => (int) $request->requested_posts_count,
            'description' => $request->description,
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
                    'objective' => $request->objective,
                    'description' => $request->description,
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                    'channels' => $channels,
                    'requested_posts_count' => (int) $request->requested_posts_count,
                ],
            ],
        ]);

        /*
         * Phase 6 will replace this demo output with real Claude generation.
         * For now we create placeholder posts so the campaign output page can be tested end-to-end.
         */
        $this->createDemoPosts($campaign);

        $campaign->update([
            'status' => 'generated',
        ]);

        return redirect()
            ->route('agency.campaigns.show', $campaign)
            ->with('success', 'Campaign generated successfully.');
    }

    public function show(Request $request, Campaign $campaign): View
    {
        abort_if($campaign->user_id !== $request->user()->id, 403);

        $campaign->load(['posts', 'client', 'persona']);

        return view('agency.campaigns.show', [
            'campaign' => $campaign,
        ]);
    }

    private function createDemoPosts(Campaign $campaign): void
    {
        $campaign->posts()->delete();

        $channels = $campaign->channels;
        $startDate = $campaign->start_date->copy();

        for ($i = 1; $i <= $campaign->requested_posts_count; $i++) {
            $channel = $channels[($i - 1) % count($channels)];
            $scheduledDate = $startDate->copy()->addDays((int) floor(($i - 1) / count($channels)));

            $campaign->posts()->create([
                'sequence_number' => $i,
                'scheduled_date' => $scheduledDate->toDateString(),
                'channel' => ucfirst($channel),
                'media_type' => $channel === 'instagram' ? 'Reel' : 'Image',
                'summary' => "Generated post {$i} for {$campaign->name}.",
                'caption' => "This is a placeholder generated caption for {$campaign->name}. Phase 6 will replace this with real AI-generated content.",
                'hashtags' => '#MARKETHING #MarketingCampaign #GeneratedContent',
                'creative_direction' => 'Use a clean, on-brand visual direction based on the selected client profile and persona.',
                'is_edited' => false,
            ]);
        }
    }
}