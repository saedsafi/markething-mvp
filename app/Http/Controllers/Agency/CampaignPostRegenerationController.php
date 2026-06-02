<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\CampaignPost;
use App\Services\AI\PostRegenerationService;
use Illuminate\Http\RedirectResponse;
use App\Services\AppSettingService;

class CampaignPostRegenerationController extends Controller
{
    public function store(
        CampaignPost $post
    ): RedirectResponse {

        if (
            $post->campaign->user_id !==
            auth()->id()
        ) {
            abort(403);
        }

        $limit = app(AppSettingService::class)
        ->int('max_regenerations_per_post', 1);
    
        if ($post->regeneration_count >= $limit) {
                    return back()->withErrors([
                'regeneration' =>
                    'This post has already been regenerated once. Regeneration limit reached.',
            ]);
        }

        app(PostRegenerationService::class)
            ->regenerate($post);

        return back()->with(
            'success',
            'Campaign post regenerated successfully.'
        );
    }
}