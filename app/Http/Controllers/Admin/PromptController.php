<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePromptVersionRequest;
use App\Http\Requests\Admin\TestPromptRequest;
use App\Models\PromptTemplate;
use App\Models\PromptVersion;
use App\Services\FakePromptTestingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\LlmLogService;

class PromptController extends Controller
{
    public function index(): View
    {
        $templates = PromptTemplate::query()
            ->with([
                'currentVersion',
                'versions' => function ($query) {
                    $query->latest();
                },
            ])
            ->latest()
            ->get();

        return view('admin.prompts.index', [
            'templates' => $templates,
        ]);
    }

    public function storeVersion(
        StorePromptVersionRequest $request
    ): RedirectResponse {

        $template = PromptTemplate::findOrFail(
            $request->prompt_template_id
        );

        $latestVersion =
            $template->versions()
                ->max('version_number') ?? 0;

        $version = PromptVersion::create([

            'prompt_template_id' => $template->id,

            'version_number' => $latestVersion + 1,

            'content' => $request->content,

            'notes' => $request->notes,

            'created_by' => $request->user()->id,

            'is_active' => false,
        ]);

        return back()->with(
            'success',
            "Prompt version v{$version->version_number} created successfully."
        );
    }

    public function activateVersion(
        Request $request,
        PromptVersion $version
    ): RedirectResponse {

        $template = $version->template;

        $template->versions()
            ->update([
                'is_active' => false,
            ]);

        $version->update([
            'is_active' => true,
        ]);

        $template->update([
            'current_version_id' => $version->id,
        ]);

        return back()->with(
            'success',
            "Prompt version v{$version->version_number} is now active."
        );
    }

    public function test(
        TestPromptRequest $request
    ): RedirectResponse {

        $result =
            app(FakePromptTestingService::class)
                ->generate(
                    $request->prompt,
                    $request->test_input
                );

                app(LlmLogService::class)->create([

                    'user_id' => $request->user()->id,
                
                    'type' => 'prompt_test',
                
                    'provider' => 'fake-ai',
                
                    'model' => 'simulation-engine-v1',
                
                    'request_payload' => [
                        'prompt' => $request->prompt,
                        'test_input' => $request->test_input,
                    ],
                
                    'response_payload' => [
                        'response' => $result['response'],
                    ],
                
                    'tokens_input' => $result['tokens'],
                
                    'tokens_output' => rand(400, 1200),
                
                    'latency_ms' =>
                        (int) filter_var($result['latency'], FILTER_SANITIZE_NUMBER_INT) * 1000,
                
                    'status' => $result['status'],
                ]);
        return back()
            ->with('test_result', $result)
            ->withInput();
    }
}