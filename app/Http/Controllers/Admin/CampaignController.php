<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function index(Request $request): View
    {
        $query = Campaign::query()
            ->with([
                'user',
                'client',
                'persona',
            ])
            ->withCount('posts');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "{$search}%");
                    })
                    ->orWhereHas('client', function ($clientQuery) use ($search) {
                        $clientQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('industry', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        switch ($request->sort) {
            case 'posts_desc':
                $query->orderByDesc('posts_count');
                break;

            case 'posts_asc':
                $query->orderBy('posts_count');
                break;

            case 'name_asc':
                $query->orderBy('name');
                break;

            case 'name_desc':
                $query->orderByDesc('name');
                break;

            case 'oldest':
                $query->oldest();
                break;

            default:
                $query->latest();
                break;
        }

        $campaigns =
            $query->paginate(15)
                ->withQueryString();

        $allCampaigns = Campaign::query()->get();

        return view('admin.campaigns.index', [
            'campaigns' => $campaigns,
            'totalCampaigns' => $allCampaigns->count(),
            'generatedCampaigns' => $allCampaigns->where('status', 'generated')->count(),
            'generatingCampaigns' => $allCampaigns->where('status', 'generating')->count(),
            'failedCampaigns' => $allCampaigns->where('status', 'failed')->count(),
        ]);
    }

    public function show(Campaign $campaign): View
    {
        $campaign->load([
            'user',
            'client',
            'persona',
            'posts',
            'llmLogs',
        ]);

        $totalInputTokens =
            $campaign->llmLogs->sum('input_tokens');

        $totalOutputTokens =
            $campaign->llmLogs->sum('output_tokens');

        return view('admin.campaigns.show', [
            'campaign' => $campaign,
            'totalInputTokens' => $totalInputTokens,
            'totalOutputTokens' => $totalOutputTokens,
            'totalTokens' => $totalInputTokens + $totalOutputTokens,
            'generationLogs' => $campaign->llmLogs,
        ]);
    }

    public function destroy(Campaign $campaign): RedirectResponse
    {
        $campaign->delete();

        return redirect()
            ->route('admin.campaigns.index')
            ->with('success', 'Campaign deleted successfully.');
    }
}