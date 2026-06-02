<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\AiAssistRequest;
use App\Models\Client;
use App\Services\AI\ClaudeService;
use App\Services\AI\PromptCompilerService;
use App\Services\AI\PromptTemplateService;
use App\Services\AI\AiAssistLimitService;
use App\Services\AI\LlmLogService;
use App\Services\AI\TextLimitService;
use Illuminate\Http\JsonResponse;

class AiAssistController extends Controller
{
    public function __invoke(
        AiAssistRequest $request
    ): JsonResponse {

        $user = $request->user();

        $limitService =
            app(AiAssistLimitService::class);

        if (
            $limitService->hasReachedDailyLimit(
                $user
            )
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Daily AI assist limit reached. Resets at midnight.',
                'remaining' => 0,
            ], 429);
        }

        $client = null;
        $businessContext = null;
        $businessInfo = [];
        $brandInfo = [];

        if ($request->filled('client_id')) {

            $client = Client::query()
                ->where('id', $request->client_id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            $businessContext =
                $client->business_context;

            $businessInfo =
                $client->business_info ?? [];

            $brandInfo =
                $client->brand_info ?? [];

        } else {

            $businessContext =
                $request->business_context;

            $businessInfo =
                $request->business_info ?? [];

            $brandInfo =
                $request->brand_info ?? [];
        }

        $promptVersion =
            app(PromptTemplateService::class)
                ->getAssistPrompt(
                    $request->question_key
                );

        if (! $promptVersion) {

            return response()->json([
                'success' => false,
                'message' =>
                    'No active assist prompt found.',
            ], 422);
        }

        $characterLimit =
            (int) ($request->character_limit ?? 500);

        $compiledPrompt =
            app(PromptCompilerService::class)
                ->compile(
                    $promptVersion->content,
                    [

                        'business_context' =>
                            $businessContext,

                        'business_info' =>
                            json_encode(
                                $businessInfo,
                                JSON_PRETTY_PRINT |
                                JSON_UNESCAPED_UNICODE
                            ),

                        'brand_info' =>
                            json_encode(
                                $brandInfo,
                                JSON_PRETTY_PRINT |
                                JSON_UNESCAPED_UNICODE
                            ),

                        'question_label' =>
                            $request->question_label,

                        'user_input' =>
                            $request->input,

                        'character_limit' =>
                            $characterLimit,

                        'extra_instructions' =>
                            $request->extra_instructions
                                ?? '',
                    ]
                );

        $startedAt = microtime(true);

        try {

            $result =
                app(ClaudeService::class)
                    ->generate(
                        $compiledPrompt
                    );

            $latency =
                (int) (
                    (microtime(true) - $startedAt)
                    * 1000
                );

            $limitedOutput =
                app(TextLimitService::class)
                    ->truncateAtWordBoundary(
                        $result['content'],
                        $characterLimit
                    );

            app(LlmLogService::class)
                ->create([

                    'user_id' =>
                        $user->id,

                    'client_id' =>
                        $client?->id,

                    'call_type' =>
                        'ai_assist',

                    'provider' =>
                        'anthropic',

                    'model' =>
                        config(
                            'ai.anthropic.model'
                        ),

                    'prompt_version_id' =>
                        $promptVersion->id,

                    'question_key' =>
                        $request->question_key,

                    'assembled_prompt' =>
                        $compiledPrompt,

                    'response' => [
                        'original' =>
                            $result['content'],

                        'final' =>
                            $limitedOutput['text'],

                        'truncated' =>
                            $limitedOutput['truncated'],
                    ],

                    'input_tokens' =>
                        $result['input_tokens'] ?? 0,

                    'output_tokens' =>
                        $result['output_tokens'] ?? 0,

                    'latency_ms' =>
                        $latency,

                    'status' =>
                        'success',
                ]);

            return response()->json([

                'success' => true,

                'text' =>
                    $limitedOutput['text'],

                'remaining' =>
                    $limitService->remainingToday($user),
            ]);

        } catch (\Throwable $exception) {

            app(LlmLogService::class)
                ->create([

                    'user_id' =>
                        $user->id,

                    'client_id' =>
                        $client?->id,

                    'call_type' =>
                        'ai_assist',

                    'provider' =>
                        'anthropic',

                    'model' =>
                        config(
                            'ai.anthropic.model'
                        ),

                    'prompt_version_id' =>
                        $promptVersion->id,

                    'question_key' =>
                        $request->question_key,

                    'assembled_prompt' =>
                        $compiledPrompt,

                    'response' => null,

                    'input_tokens' => 0,

                    'output_tokens' => 0,

                    'latency_ms' => 0,

                    'status' => 'failed',

                    'error_message' =>
                        $exception->getMessage(),
                ]);

            return response()->json([

                'success' => false,

                'message' =>
                    'AI Assist generation failed.',
            ], 500);
        }
    }
}