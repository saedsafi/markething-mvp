<?php

namespace App\Services\AI;

use App\Models\Campaign;
use App\Models\CampaignPost;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CampaignGenerationService
{
    public function generate(Campaign $campaign): void
    {
        $compiledPrompt = null;
        $promptVersion = null;

        try {
            DB::beginTransaction();

            $campaign->load([
                'client',
                'persona',
            ]);

            $snapshot = $campaign->snapshot ?? [];

            $snapshotClient = $snapshot['client'] ?? [];
            $snapshotPersona = $snapshot['persona'] ?? [];
            $snapshotCampaign = $snapshot['campaign'] ?? [];

            $promptVersion =
                app(PromptTemplateService::class)
                    ->getActiveMasterPrompt();

            if (! $promptVersion) {
                throw new \Exception('No active master prompt found.');
            }

            $campaignOffer =
                $snapshotCampaign['offer'] ?? [];

            $campaignConversionMethods =
                $snapshotCampaign['conversion_methods'] ?? [];

            $campaignFormatMode =
                $snapshotCampaign['format_mode'] ?? null;

            $campaignMood =
                $snapshotCampaign['mood'] ?? null;

            $compiledPrompt =
                app(PromptCompilerService::class)
                    ->compile(
                        $promptVersion->content,
                        [
                            'business_context' =>
                                $snapshotClient['business_context'] ?? '',

                                'business_info' =>
                                app(PromptContextFormatterService::class)
                                    ->businessInfo(
                                        $snapshotClient['business_info'] ?? []
                                    ),

                                    'brand_info' =>
                                    app(PromptContextFormatterService::class)
                                        ->brandInfo(
                                            $snapshotClient['brand_info'] ?? []
                                        ),

                                        'persona' =>
                                        app(PromptContextFormatterService::class)
                                            ->persona(
                                                $snapshotPersona['answers'] ?? []
                                            ),

                            'campaign_topic' =>
                                $snapshotCampaign['topic']
                                ?? $snapshotCampaign['name']
                                ?? $campaign->name,

                            'campaign_objective' =>
                                $snapshotCampaign['objective'] ?? $campaign->objective,

                            'campaign_description' =>
                                $snapshotCampaign['description'] ?? $campaign->description,

                            'campaign_offer' =>
                                json_encode(
                                    $campaignOffer,
                                    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
                                ),

                            'conversion_methods' =>
                                implode(', ', $campaignConversionMethods),

                            'format_mode' =>
                                $campaignFormatMode ?? 'Let the system decide',

                            'campaign_mood' =>
                                $campaignMood ?: 'Not specified',

                            'channels' =>
                                implode(
                                    ', ',
                                    $snapshotCampaign['channels'] ?? $campaign->channels
                                ),

                            'posts_count' =>
                                $snapshotCampaign['requested_posts_count']
                                ?? $campaign->requested_posts_count,

                            'start_date' =>
                                $snapshotCampaign['start_date'] ?? $campaign->start_date,

                            'end_date' =>
                                $snapshotCampaign['end_date'] ?? $campaign->end_date,
                        ]
                    );

            $compiledPrompt .= '

CAMPAIGN FORM DETAILS
=====================

Campaign topic:
' . (
                $snapshotCampaign['topic']
                ?? $snapshotCampaign['name']
                ?? $campaign->name
            ) . '

Objective:
' . (
                $snapshotCampaign['objective']
                ?? $campaign->objective
            ) . '

Offer details:
' . json_encode(
                $campaignOffer,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            ) . '

Conversion methods:
' . (
                count($campaignConversionMethods)
                    ? implode(', ', $campaignConversionMethods)
                    : 'Not provided'
            ) . '

Format mode:
' . ($campaignFormatMode ?? 'Let the system decide') . '

Campaign mood:
' . ($campaignMood ?: 'Not specified') . '

Use these campaign form details when deciding captions, CTAs, media type, creative direction, and post angles.

FORMAT MODE RULES:
- If format mode is Images only, every post media_type must be "image".
- If format mode is Reels only, every post media_type must be "reel".
- If format mode is Carousels only, every post media_type must be "carousel".
- If format mode is Let the system decide, choose the best media_type for each post from: image, reel, carousel.

CONVERSION METHOD RULES:
- CTAs must use the selected conversion methods when relevant.
- Do not invent conversion methods that were not selected.
- If WhatsApp is selected, use WhatsApp CTAs when relevant.
- If website/link is selected, use link/website CTAs when relevant.
- If social DM is selected, use DM CTAs when relevant.
- If store/location is selected, use visit/location CTAs when relevant.

OFFER RULES:
- If objective is Offer / promotion, use the offer details exactly as provided.
- Do not invent discounts, deadlines, promo codes, or conditions.
- If no offer detail is provided, do not fabricate one.

IMPORTANT OUTPUT RULES:

Return ONLY valid JSON.

Return an array of posts.

Each post MUST contain:
- sequence_number
- caption
- hashtags
- channel
- scheduled_date
- creative_direction
- media_type
- summary

Do NOT return markdown.
Do NOT wrap JSON in code blocks.
';

            $startedAt = microtime(true);

            $result =
                app(ClaudeService::class)
                    ->generate(
                        $compiledPrompt . '

CRITICAL OUTPUT RULES:

- Return ONLY raw valid JSON.
- Do NOT explain anything.
- Do NOT use markdown.
- Do NOT wrap JSON in ```json blocks.
- Response MUST start with [.
- Response MUST end with ].

Generate exactly ' .
                        (
                            $snapshotCampaign['requested_posts_count']
                            ?? $campaign->requested_posts_count
                        ) .
                        ' posts.
'
                    );

            $latency =
                (int) ((microtime(true) - $startedAt) * 1000);
                
                \Log::info('=== CLAUDE RAW RESPONSE ===');
                \Log::info($result['content']);
            $posts =
                app(AIResponseParser::class)
                    ->parseCampaignPosts(
                        $result['content']
                    );

            $requestedPostsCount =
                (int) (
                    $snapshotCampaign['requested_posts_count']
                    ?? $campaign->requested_posts_count
                );

            $campaignStartDate =
                Carbon::parse(
                    $snapshotCampaign['start_date']
                    ?? $campaign->start_date
                )->toDateString();

            $campaignEndDate =
                Carbon::parse(
                    $snapshotCampaign['end_date']
                    ?? $campaign->end_date
                )->toDateString();

            $allowedChannels = [
                'instagram',
                'facebook',
            ];

            $allowedMediaTypes = [
                'image',
                'reel',
                'carousel',
            ];

            if (count($posts) !== $requestedPostsCount) {
                throw new \Exception(
                    'Claude returned ' . count($posts) . " posts, but {$requestedPostsCount} were requested."
                );
            }

            $seenScheduleSlots = [];

            foreach ($posts as $index => $post) {
                $channel =
                    strtolower($post['channel'] ?? '');

                if (! in_array($channel, $allowedChannels, true)) {
                    throw new \Exception(
                        'Generated post #' . ($index + 1) . ' has an invalid channel.'
                    );
                }

                if (empty($post['scheduled_date'])) {
                    throw new \Exception(
                        'Generated post #' . ($index + 1) . ' is missing scheduled_date.'
                    );
                }

                $scheduledDate =
                    Carbon::parse(
                        $post['scheduled_date']
                    )->toDateString();

                if (
                    $scheduledDate < $campaignStartDate ||
                    $scheduledDate > $campaignEndDate
                ) {
                    throw new \Exception(
                        'Generated post #' . ($index + 1) . ' has a scheduled date outside the campaign date range.'
                    );
                }

                $slotKey =
                    $channel . '|' . $scheduledDate;

                if (isset($seenScheduleSlots[$slotKey])) {
                    throw new \Exception(
                        "Claude returned more than one {$channel} post on {$scheduledDate}."
                    );
                }

                $seenScheduleSlots[$slotKey] = true;

                $mediaType =
                    strtolower($post['media_type'] ?? 'image');

                if (! in_array($mediaType, $allowedMediaTypes, true)) {
                    throw new \Exception(
                        'Generated post #' . ($index + 1) . ' has an invalid media type.'
                    );
                }

                if ($campaignFormatMode === 'Images only' && $mediaType !== 'image') {
                    throw new \Exception(
                        'Generated post #' . ($index + 1) . ' violates the Images only format mode.'
                    );
                }

                if ($campaignFormatMode === 'Reels only' && $mediaType !== 'reel') {
                    throw new \Exception(
                        'Generated post #' . ($index + 1) . ' violates the Reels only format mode.'
                    );
                }

                if ($campaignFormatMode === 'Carousels only' && $mediaType !== 'carousel') {
                    throw new \Exception(
                        'Generated post #' . ($index + 1) . ' violates the Carousels only format mode.'
                    );
                }
            }

            $posts = collect($posts)
                ->unique('caption')
                ->values()
                ->toArray();

            if (count($posts) !== $requestedPostsCount) {
                throw new \Exception(
                    'Claude returned duplicate captions, causing the generated post count to be lower than requested.'
                );
            }

            foreach ($posts as $index => $post) {
                CampaignPost::create([
                    'campaign_id' =>
                        $campaign->id,

                    'sequence_number' =>
                        $post['sequence_number'] ?? ($index + 1),

                    'scheduled_date' =>
                        Carbon::parse($post['scheduled_date']),

                    'channel' =>
                        strtolower($post['channel']),

                    'media_type' =>
                        strtolower($post['media_type'] ?? 'image'),

                    'summary' =>
                        $post['summary'] ?? null,

                    'caption' =>
                        $post['caption'] ?? '',

                    'hashtags' =>
                        is_array($post['hashtags'] ?? null)
                            ? implode(' ', $post['hashtags'])
                            : ($post['hashtags'] ?? ''),

                    'creative_direction' =>
                        $post['creative_direction'] ?? '',

                    'is_edited' => false,
                ]);
            }

            $campaign->update([
                'status' => 'generated',
            ]);

            app(LlmLogService::class)
                ->create([
                    'user_id' =>
                        $campaign->user_id,

                    'client_id' =>
                        $campaign->client_id,

                    'campaign_id' =>
                        $campaign->id,

                    'call_type' =>
                        'campaign_generation',

                    'provider' =>
                        'anthropic',

                    'model' =>
                        config('ai.anthropic.model'),

                    'prompt_version_id' =>
                        $promptVersion->id,

                    'assembled_prompt' =>
                        $compiledPrompt,

                    'response' =>
                        $result['content'],

                    'input_tokens' =>
                        $result['input_tokens'] ?? 0,

                    'output_tokens' =>
                        $result['output_tokens'] ?? 0,

                    'latency_ms' =>
                        $latency,

                    'retry_count' => 0,

                    'status' =>
                        'success',
                ]);

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();

            $campaign->update([
                'status' => 'failed',
            ]);

            app(LlmLogService::class)
                ->create([
                    'user_id' =>
                        $campaign->user_id,

                    'client_id' =>
                        $campaign->client_id,

                    'campaign_id' =>
                        $campaign->id,

                    'call_type' =>
                        'campaign_generation',

                    'provider' =>
                        'anthropic',

                    'model' =>
                        config('ai.anthropic.model'),

                    'prompt_version_id' =>
                        $promptVersion?->id,

                    'assembled_prompt' =>
                        $compiledPrompt,

                    'response' => null,

                    'input_tokens' => 0,

                    'output_tokens' => 0,

                    'latency_ms' => 0,

                    'retry_count' => 0,

                    'status' =>
                        'failed',

                    'error_message' =>
                        $e->getMessage(),
                ]);

            throw $e;
        }
    }
}