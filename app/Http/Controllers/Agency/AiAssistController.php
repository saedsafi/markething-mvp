<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\AiAssistRequest;
use App\Services\FakeAiAssistService;
use App\Services\LlmLogService;
use Illuminate\Http\JsonResponse;

class AiAssistController extends Controller
{
    public function __invoke(
        AiAssistRequest $request
    ): JsonResponse {

        $result =
            app(FakeAiAssistService::class)
                ->generate($request->validated());

        app(LlmLogService::class)->create([

            'user_id' => $request->user()->id,

            'type' => 'ai_assist',

            'provider' => 'fake-ai',

            'model' => 'simulation-engine-v1',

            'request_payload' =>
                $request->validated(),

            'response_payload' => [
                'text' => $result['text'],
            ],

            'tokens_input' =>
                $result['tokens'],

            'tokens_output' =>
                rand(120, 600),

            'latency_ms' =>
                $result['latency_ms'],

            'status' =>
                $result['status'],
        ]);

        return response()->json([
            'success' => true,
            'text' => $result['text'],
        ]);
    }
}