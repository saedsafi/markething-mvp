<?php

namespace App\Services\AI;

use App\Models\PromptTemplate;

class PromptTemplateService
{
    public function getActiveMasterPrompt()
    {
        $template = PromptTemplate::query()
            ->where('type', 'master')
            ->where('is_active', true)
            ->with([
                'currentVersion',
                'testVersions' => function ($query) {
                    $query
                        ->where('is_active', true)
                        ->latest();
                },
            ])
            ->first();

        return $this->resolveVersion($template);
    }

    public function getAssistPrompt(
        string $questionKey
    ) {
        $template = PromptTemplate::query()
            ->where('type', 'assist')
            ->where('question_key', $questionKey)
            ->where('is_active', true)
            ->with([
                'currentVersion',
                'testVersions' => function ($query) {
                    $query
                        ->where('is_active', true)
                        ->latest();
                },
            ])
            ->first();

        return $this->resolveVersion($template);
    }

    protected function resolveVersion(
        ?PromptTemplate $template
    ) {
        if (! $template) {
            return null;
        }

        if (
            auth()->user()?->uses_test_prompts &&
            $template->testVersions->isNotEmpty()
        ) {
            return $template->testVersions->first();
        }

        return $template->currentVersion;
    }
}