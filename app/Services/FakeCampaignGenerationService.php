<?php

namespace App\Services;

use App\Models\Campaign;
use Carbon\Carbon;
use App\Services\LlmLogService;

class FakeCampaignGenerationService
{
    public function generate(Campaign $campaign): void
    {
        $campaign->posts()->delete();

        $channels = $campaign->channels;

        $startDate = Carbon::parse($campaign->start_date);

        $clientName = $campaign->client->name;
        $objective = $campaign->objective;

        $captions = [
            "Ready to elevate your experience with {$clientName}? Discover what makes this campaign special.",
            "Your next favorite experience starts here. {$clientName} is bringing something exciting.",
            "Looking for something fresh and engaging? {$clientName} has you covered.",
            "This is your sign to explore what {$clientName} has been preparing for you.",
            "New energy, fresh ideas, and creative experiences — only with {$clientName}.",
        ];

        $creativeDirections = [
            "Use soft luxury lighting with clean brand-focused composition.",
            "Create modern lifestyle visuals with authentic human interaction.",
            "Focus on premium minimalist product presentation.",
            "Use energetic motion-based content with vibrant transitions.",
            "Blend cinematic storytelling with modern social-first visuals.",
        ];

        $hashtags = [
            "#Marketing #BrandGrowth #SocialMedia",
            "#ContentCreation #DigitalMarketing #Campaign",
            "#BrandAwareness #MarketingStrategy #Creative",
            "#SocialGrowth #MarketingTips #OnlineBusiness",
            "#Reels #InstagramMarketing #BusinessGrowth",
        ];

        for ($i = 1; $i <= $campaign->requested_posts_count; $i++) {

            $channel = $channels[($i - 1) % count($channels)];

            $scheduledDate = $startDate
                ->copy()
                ->addDays((int) floor(($i - 1) / count($channels)));

            $caption =
                $captions[array_rand($captions)];

            $creativeDirection =
                $creativeDirections[array_rand($creativeDirections)];

            $hashtagSet =
                $hashtags[array_rand($hashtags)];

            $campaign->posts()->create([
                'sequence_number' => $i,

                'scheduled_date' => $scheduledDate,

                'channel' => ucfirst($channel),

                'media_type' => $channel === 'instagram'
                    ? collect(['Reel', 'Carousel', 'Image'])->random()
                    : collect(['Image', 'Story'])->random(),

                'summary' => "{$objective} Campaign Post {$i}",

                'caption' => $caption,

                'hashtags' => $hashtagSet,

                'creative_direction' => $creativeDirection,

                'is_edited' => false,
            ]);
        }
        
        app(LlmLogService::class)->create([

            'user_id' => $campaign->user_id,
        
            'campaign_id' => $campaign->id,
        
            'prompt_version_id' =>
                $campaign->prompt_version_id,
        
            'type' => 'campaign_generation',
        
            'provider' => 'fake-ai',
        
            'model' => 'simulation-engine-v1',
        
            'request_payload' => [
                'campaign' => $campaign->snapshot,
            ],
        
            'response_payload' => [
                'generated_posts' =>
                    $campaign->posts()->count(),
            ],
        
            'tokens_input' => rand(1200, 5000),
        
            'tokens_output' => rand(800, 3200),
        
            'latency_ms' => rand(1200, 4200),
        
            'status' => 'success',
        ]);
        
        $campaign->update([
            'status' => 'generated',
        ]);
    }
}