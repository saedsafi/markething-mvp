<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePromptVersionRequest;
use App\Http\Requests\Admin\TestPromptRequest;
use App\Models\PromptTemplate;
use App\Models\PromptVersion;
use App\Models\TestPromptVersion;
use App\Services\AI\ClaudeService;
use App\Services\LlmLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromptController extends Controller
{
    public function index(): View
    {
        $templates = PromptTemplate::query()
            ->with([
                'currentVersion',
                'versions' => fn ($query) => $query->latest(),
                'testVersions' => fn ($query) => $query->latest(),
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

        $latestVersion = $template->versions()
            ->latest('id')
            ->first();

        $nextVersion = $this->nextVersionNumber(
            $latestVersion?->version
        );

        $version = PromptVersion::create([
            'prompt_template_id' => $template->id,
            'version' => $nextVersion,
            'content' => $request->content,
            'created_by' => $request->user()->id,
            'is_active' => false,
        ]);

        return back()->with(
            'success',
            "Prompt version {$version->version} created successfully."
        );
    }

    public function activateVersion(
        Request $request,
        PromptVersion $version
    ): RedirectResponse {
        $template = $version->template;

        $template->versions()->update([
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
            "Prompt version {$version->version} is now active."
        );
    }

    public function storeTestVersion(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'prompt_template_id' => [
                'required',
                'exists:prompt_templates,id',
            ],
            'content' => [
                'required',
                'string',
            ],
        ]);

        $template = PromptTemplate::findOrFail(
            $validated['prompt_template_id']
        );

        $latestVersion = $template->testVersions()
            ->latest('id')
            ->first();

        $nextVersion = $this->nextVersionNumber(
            $latestVersion?->version
        );

        $version = TestPromptVersion::create([
            'prompt_template_id' => $template->id,
            'version' => $nextVersion,
            'content' => $validated['content'],
            'created_by' => $request->user()->id,
            'is_active' => false,
        ]);

        return back()->with(
            'success',
            "Test prompt version {$version->version} created successfully."
        );
    }

    public function activateTestVersion(
        Request $request,
        TestPromptVersion $version
    ): RedirectResponse {
        $template = $version->template;

        $template->testVersions()->update([
            'is_active' => false,
        ]);

        $version->update([
            'is_active' => true,
        ]);

        return back()->with(
            'success',
            "Test prompt version {$version->version} is now active."
        );
    }

    public function test(
        TestPromptRequest $request
    ): RedirectResponse {
        $startedAt = microtime(true);

        $assembledPrompt =
            $request->prompt .
            "\n\n" .
            $request->test_input;

        $result = app(ClaudeService::class)
            ->generate($assembledPrompt);

        $latency = (int) (
            (microtime(true) - $startedAt) * 1000
        );

        app(LlmLogService::class)->create([
            'user_id' => $request->user()->id,
            'call_type' => 'prompt_test',
            'provider' => 'anthropic',
            'model' => config('ai.anthropic.model'),
            'assembled_prompt' => [
                'prompt' => $request->prompt,
                'test_input' => $request->test_input,
            ],
            'response' => [
                'response' => $result['content'] ?? '',
            ],
            'input_tokens' => $result['input_tokens'] ?? 0,
            'output_tokens' => $result['output_tokens'] ?? 0,
            'latency_ms' => $latency,
            'status' => 'success',
        ]);

        return back()
            ->with('test_result', [
                'assembled_prompt' => $assembledPrompt,
                'response' => $result['content'] ?? '',
                'tokens' =>
                    ($result['input_tokens'] ?? 0) +
                    ($result['output_tokens'] ?? 0),
                'latency' => $latency . 'ms',
                'status' => 'success',
            ])
            ->withInput();
    }

    protected function nextVersionNumber(
        ?string $currentVersion
    ): string {
        if (! $currentVersion) {
            return 'v1.0';
        }

        $number = (float) str_replace(
            'v',
            '',
            $currentVersion
        );

        return 'v' . number_format(
            $number + 0.1,
            1
        );
    }

    public function promoteTestVersion(
        TestPromptVersion $version
    ): RedirectResponse {
    
        $template = $version->template;
    
        $latestVersion =
            $template->versions()
                ->latest('id')
                ->first();
    
        $nextVersion =
            $this->nextVersionNumber(
                $latestVersion?->version
            );
    
        $productionVersion =
            PromptVersion::create([
    
                'prompt_template_id' =>
                    $template->id,
    
                'version' =>
                    $nextVersion,
    
                'content' =>
                    $version->content,
    
                'created_by' =>
                    auth()->id(),
    
                'is_active' =>
                    false,
            ]);
    
        return back()->with(
            'success',
            "Test prompt {$version->version} promoted to production version {$productionVersion->version}."
        );
    }
}