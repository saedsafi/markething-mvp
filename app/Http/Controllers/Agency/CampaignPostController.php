<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\UpdateCampaignPostRequest;
use App\Models\CampaignPost;
use Illuminate\Http\RedirectResponse;

class CampaignPostController extends Controller
{
    public function update(
        UpdateCampaignPostRequest $request,
        CampaignPost $post
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Security
        |--------------------------------------------------------------------------
        */

        if (
            $post->campaign->user_id !==
            auth()->id()
        ) {
            abort(403);
        }

        $post->update([

            'caption' =>
                $request->caption,

            'hashtags' =>
                $request->hashtags,

            'creative_direction' =>
                $request->creative_direction,

            'is_edited' => true,
        ]);

        return back()->with(
            'success',
            'Campaign post updated successfully.'
        );
    }
}