<?php

namespace App\Services\AI;

use App\Models\CampaignPost;
use Illuminate\Support\Facades\DB;

class PostRegenerationService
{
    public function regenerate(CampaignPost $post): void
    {
        if ((int) $post->regeneration_count >= 1) {
            throw new \Exception(
                'This post has already been regenerated once.'
            );
        }

        $campaign = $post->campaign;

        $campaign->load([
            'client',
            'persona',
        ]);

        $snapshot =
            $campaign->snapshot ?? [];

        $snapshotClient =
            $snapshot['client'] ?? [];

        $snapshotPersona =
            $snapshot['persona'] ?? [];

        $snapshotCampaign =
            $snapshot['campaign'] ?? [];

        $promptVersion =
            app(PromptTemplateService::class)
                ->getActiveMasterPrompt();

        if (! $promptVersion) {
            throw new \Exception(
                'No active master prompt found.'
            );
        }

        $compiledPrompt =
            app(PromptCompilerService::class)
                ->compile(
                    $promptVersion->content,
                    [
                        'business_context' =>
                            $snapshotClient['business_context']
                            ?? '',

                        'business_info' =>
                            json_encode(
                                $snapshotClient['business_info']
                                ?? [],
                                JSON_PRETTY_PRINT |
                                JSON_UNESCAPED_UNICODE
                            ),

                        'brand_info' =>
                            json_encode(
                                $snapshotClient['brand_info']
                                ?? [],
                                JSON_PRETTY_PRINT |
                                JSON_UNESCAPED_UNICODE
                            ),

                        'persona' =>
                            json_encode(
                                $snapshotPersona['answers']
                                ?? [],
                                JSON_PRETTY_PRINT |
                                JSON_UNESCAPED_UNICODE
                            ),

                        'campaign_objective' =>
                            $snapshotCampaign['objective']
                            ?? $campaign->objective,

                        'campaign_description' =>
                            $snapshotCampaign['description']
                            ?? $campaign->description,

                        'channels' =>
                            implode(
                                ', ',
                                $snapshotCampaign['channels']
                                ?? $campaign->channels
                            ),

                        'posts_count' => 1,

                        'start_date' =>
                            $snapshotCampaign['start_date']
                            ?? $campaign->start_date,

                        'end_date' =>
                            $snapshotCampaign['end_date']
                            ?? $campaign->end_date,
                    ]
                );

        $compiledPrompt .= '

REGENERATION TASK
=================

Regenerate ONLY ONE campaign post.

You are replacing the current post with a fresh improved version.

CURRENT POST
============

Sequence Number:
' . $post->sequence_number . '

Channel:
' . $post->channel . '

Scheduled Date:
' . optional($post->scheduled_date)->format('Y-m-d') . '

Current Summary:
' . $post->summary . '

Current Caption:
' . $post->caption . '

Current Hashtags:
' . $post->hashtags . '

Current Creative Direction:
' . $post->creative_direction . '

STRICT REGENERATION RULES
=========================

1. Keep the same campaign objective, same target persona, same brand rules, and same campaign strategy.

2. Do NOT repeat the exact wording of the current caption.

3. Keep the same selected channel:
' . $post->channel . '

4. Keep the same scheduled date:
' . optional($post->scheduled_date)->format('Y-m-d') . '

5. You MUST strictly follow the saved Arabic dialect / variety from BRAND INFORMATION.

6. Do NOT default to Modern Standard Arabic unless BRAND INFORMATION explicitly says Modern Standard Arabic.

7. You MUST follow:
   - Arabic dialect / variety
   - emoji usage
   - English usage
   - words and phrases to avoid
   - conversion actions
   - business differentiator
   - persona priorities
   - persona objections
   - buyer/user relationship

8. The regenerated caption must feel native to the selected dialect and audience.

9. Respect the configured emoji preference:
   - None: use no emojis.
   - Minimal: use very few emojis.
   - Moderate: use balanced emojis.
   - Liberal: use frequent but tasteful emojis.

10. Respect the configured English usage preference.

11. If words or phrases to avoid are provided, never use them.

12. CTAs must align with the configured conversion actions whenever possible.

13. Keep the output platform-ready.

14. Keep the creative direction detailed enough for content production.

15. Return ONLY raw valid JSON.

16. Do NOT use markdown.

17. Do NOT explain anything.

18. Do NOT wrap JSON in code blocks.

JSON FORMAT
===========

{
    "caption": "",
    "hashtags": "",
    "creative_direction": "",
    "media_type": "",
    "summary": ""
}
';

        $startedAt = microtime(true);

        try {
            DB::beginTransaction();

            $result =
                app(ClaudeService::class)
                    ->generate($compiledPrompt);

            $latency =
                (int) (
                    (microtime(true) - $startedAt)
                    * 1000
                );

            $response =
                trim($result['content']);

            $response = preg_replace(
                '/```json|```/',
                '',
                $response
            );

            preg_match(
                '/\{.*\}/s',
                $response,
                $matches
            );

            $json =
                $matches[0] ?? $response;

            $decoded =
                json_decode($json, true);

            if (! is_array($decoded)) {
                throw new \Exception(
                    'Claude returned invalid regeneration JSON.'
                );
            }

            $post->update([
                'caption' =>
                    $decoded['caption']
                    ?? $post->caption,

                'hashtags' =>
                    is_array($decoded['hashtags'] ?? null)
                        ? implode(' ', $decoded['hashtags'])
                        : ($decoded['hashtags'] ?? $post->hashtags),

                'creative_direction' =>
                    $decoded['creative_direction']
                    ?? $post->creative_direction,

                'media_type' =>
                    $decoded['media_type']
                    ?? $post->media_type,

                'summary' =>
                    $decoded['summary']
                    ?? $post->summary,

                'is_regenerated' => true,

                'regeneration_count' =>
                    ((int) $post->regeneration_count) + 1,
            ]);

            app(LlmLogService::class)
                ->create([
                    'user_id' =>
                        $campaign->user_id,

                    'client_id' =>
                        $campaign->client_id,

                    'campaign_id' =>
                        $campaign->id,

                    'campaign_post_id' =>
                        $post->id,

                    'call_type' =>
                        'post_regeneration',

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
                        $result['input_tokens']
                        ?? 0,

                    'output_tokens' =>
                        $result['output_tokens']
                        ?? 0,

                    'latency_ms' =>
                        $latency,

                    'retry_count' => 0,

                    'status' =>
                        'success',
                ]);

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();

            app(LlmLogService::class)
                ->create([
                    'user_id' =>
                        $campaign->user_id,

                    'client_id' =>
                        $campaign->client_id,

                    'campaign_id' =>
                        $campaign->id,

                    'campaign_post_id' =>
                        $post->id,

                    'call_type' =>
                        'post_regeneration',

                    'provider' =>
                        'anthropic',

                    'model' =>
                        config('ai.anthropic.model'),

                    'prompt_version_id' =>
                        $promptVersion->id,

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