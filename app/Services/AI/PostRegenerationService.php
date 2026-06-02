<?php

namespace App\Services\AI;

use App\Models\CampaignPost;
use App\Services\LlmLogService;

class PostRegenerationService
{
    public function regenerate(
        CampaignPost $post
    ): void {

        $campaign =
            $post->campaign;

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
                    ]
                );

                $compiledPrompt .= '

                TASK:
                
                Regenerate ONLY ONE campaign post.
                
                CURRENT POST:
                
                Caption:
                ' . $post->caption . '
                
                Hashtags:
                ' . $post->hashtags . '
                
                Creative Direction:
                ' . $post->creative_direction . '
                
                IMPORTANT RULES:
                
                - Keep same campaign tone.
                - Keep same audience.
                - Keep same luxury quality.
                - Keep same platform style.
                - Do NOT repeat exact wording.
                - Create a fresh improved variation.
                - Keep output elegant and premium.
                - Output MUST remain in Modern Standard Arabic.
                
                OUTPUT RULES:
                
                - Return ONLY raw valid JSON.
                - Do NOT use markdown.
                - Do NOT explain anything.
                
                JSON FORMAT:
                
                {
                    "caption": "",
                    "hashtags": "",
                    "creative_direction": "",
                    "media_type": "",
                    "summary": ""
                }
                ';

        $result =
            app(ClaudeService::class)
                ->generate(
                    $compiledPrompt
                );

        $response =
            trim($result['content']);

        $response = preg_replace(
            '/```json|```/',
            '',
            $response
        );

        $decoded =
            json_decode($response, true);

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
                $decoded['hashtags']
                ?? $post->hashtags,

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
                $post->regeneration_count + 1,
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

                'latency_ms' => 0,

                'status' => 'success',
            ]);
    }
}