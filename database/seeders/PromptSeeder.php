<?php

namespace Database\Seeders;

use App\Models\PromptTemplate;
use App\Models\PromptVersion;
use App\Models\User;
use Illuminate\Database\Seeder;

class PromptSeeder extends Seeder
{
    public function run(): void
    {
        $founder = User::where('role', 'founder')->first();

        $master = PromptTemplate::updateOrCreate(
            ['type' => 'master', 'question_key' => null],
            [
                'name' => 'Master Campaign Prompt',
                'description' => 'Generates full campaigns and campaign posts.',
                'is_active' => true,
            ]
        );

        $masterVersion = PromptVersion::create([
            'prompt_template_id' => $master->id,
            'version' => 'v1.0',
            'content' => <<<'PROMPT'
You are MARKETHING's campaign generation engine.

Use:
{{business_info}}
{{brand_info}}
{{selected_persona}}
{{campaign_objective}}
{{campaign_dates}}
{{channels}}
{{post_count}}

Return structured JSON only.
PROMPT,
            'test_prompt' => 'Generate a sample campaign for the predetermined test user.',
            'created_by' => $founder?->id,
        ]);

        $master->update([
            'current_version_id' => $masterVersion->id,
        ]);

        $assistPrompts = [
            [
                'name' => 'Business Offer Assist',
                'question_key' => 'business_offer',
                'description' => 'Drafts the business offer answer.',
                'version' => 'v1.0',
                'content' => <<<'PROMPT'
Using {{business_context}}, draft a clear answer for:
{{question_label}}

Also consider:
{{extra_instructions}}

Match the user's language.
Keep the answer under {{character_limit}} characters.
PROMPT,
            ],
            [
                'name' => 'Brand Personality Assist',
                'question_key' => 'brand_personality',
                'description' => 'Drafts the brand personality answer.',
                'version' => 'v1.0',
                'content' => <<<'PROMPT'
Using {{business_context}}, describe the brand personality for:
{{question_label}}

Also consider:
{{extra_instructions}}

Use a concise marketing-focused tone.
PROMPT,
            ],
            [
                'name' => 'Persona Description Assist',
                'question_key' => 'persona_description',
                'description' => 'Drafts audience persona descriptions.',
                'version' => 'v1.0',
                'content' => <<<'PROMPT'
Using {{business_context}}, draft a persona answer for:
{{question_label}}

Use the user's extra popup instructions:
{{extra_instructions}}

Return a concise, practical persona description.
PROMPT,
            ],
        ];

        foreach ($assistPrompts as $assistPrompt) {
            $template = PromptTemplate::updateOrCreate(
                [
                    'type' => 'assist',
                    'question_key' => $assistPrompt['question_key'],
                ],
                [
                    'name' => $assistPrompt['name'],
                    'description' => $assistPrompt['description'],
                    'is_active' => true,
                ]
            );

            $version = PromptVersion::create([
                'prompt_template_id' => $template->id,
                'version' => $assistPrompt['version'],
                'content' => $assistPrompt['content'],
                'test_prompt' => null,
                'created_by' => $founder?->id,
            ]);

            $template->update([
                'current_version_id' => $version->id,
            ]);
        }
    }
}