<?php

namespace App\Services\AI;

use App\Models\Campaign;
use App\Models\CampaignPost;
use App\Services\AI\LlmLogService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CampaignGenerationService
{
    public function generate(
        Campaign $campaign
    ): void {

        $compiledPrompt = null;

        try {

            DB::beginTransaction();

            $campaign->load([
                'client',
                'persona',
            ]);

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
                                $campaign->client
                                    ->business_context,

                            'business_info' =>
                                json_encode(
                                    $campaign->client
                                        ->business_info,
                                    JSON_PRETTY_PRINT |
                                    JSON_UNESCAPED_UNICODE
                                ),

                            'brand_info' =>
                                json_encode(
                                    $campaign->client
                                        ->brand_info,
                                    JSON_PRETTY_PRINT |
                                    JSON_UNESCAPED_UNICODE
                                ),

                            'persona' =>
                                json_encode(
                                    $campaign->persona
                                        ->answers,
                                    JSON_PRETTY_PRINT |
                                    JSON_UNESCAPED_UNICODE
                                ),

                            'campaign_objective' =>
                                $campaign->objective,

                            'campaign_description' =>
                                $campaign->description,

                            'channels' =>
                                implode(
                                    ', ',
                                    $campaign->channels
                                ),

                            'posts_count' =>
                                $campaign
                                    ->requested_posts_count,

                            'start_date' =>
                                $campaign->start_date,

                            'end_date' =>
                                $campaign->end_date,
                        ]
                    );

            $compiledPrompt .= '

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
                        $campaign->requested_posts_count .
                        ' posts.
'
                    );

            $latency =
                (int) (
                    (microtime(true) - $startedAt)
                    * 1000
                );

            $posts =
                app(AIResponseParser::class)
                    ->parseCampaignPosts(
                        $result['content']
                    );

            /*
            |--------------------------------------------------------------------------
            | Remove duplicate captions
            |--------------------------------------------------------------------------
            */

            $posts = collect($posts)
                ->unique('caption')
                ->values()
                ->toArray();

            $allowedChannels = [
                'instagram',
                'facebook',
            ];

            foreach ($posts as $index => $post) {

                CampaignPost::create([

                    'campaign_id' =>
                        $campaign->id,

                    'sequence_number' =>
                        $post['sequence_number']
                        ?? ($index + 1),

                    'scheduled_date' =>

                        ! empty($post['scheduled_date'])

                        ? Carbon::parse(
                            $post['scheduled_date']
                        )

                        : null,

                    'channel' =>

                        in_array(
                            strtolower(
                                $post['channel']
                                ?? ''
                            ),
                            $allowedChannels
                        )

                        ? strtolower(
                            $post['channel']
                        )

                        : 'instagram',

                    'media_type' =>
                        $post['media_type']
                        ?? 'image',

                    'summary' =>
                        $post['summary']
                        ?? null,

                    'caption' =>
                        $post['caption']
                        ?? '',

                    'hashtags' =>
                        $post['hashtags']
                        ?? '',

                    'creative_direction' =>
                        $post['creative_direction']
                        ?? '',

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
                        config(
                            'ai.anthropic.model'
                        ),

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
                        config(
                            'ai.anthropic.model'
                        ),

                    'prompt_version_id' =>
                        $promptVersion->id
                        ?? null,

                    'assembled_prompt' =>
                        $compiledPrompt,

                    'response' => null,

                    'input_tokens' => 0,

                    'output_tokens' => 0,

                    'latency_ms' => 0,

                    'status' => 'failed',

                    'error_message' =>
                        $e->getMessage(),
                ]);

            throw $e;
        }
    }
}