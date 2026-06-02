<?php

namespace Database\Seeders;

use App\Models\PromptTemplate;
use App\Models\PromptVersion;
use Illuminate\Database\Seeder;

class AiAssistPromptSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [

            [
                'key' => 'business_offer',

                'name' => 'Business Offer Assist',

                'content' => '
You are an elite Arabic marketing strategist.

Generate a professional business offer description.

BUSINESS CONTEXT:
{{business_context}}

BUSINESS INFO:
{{business_info}}

USER NOTES:
{{extra_instructions}}

IMPORTANT:
- Write in professional Standard Arabic.
- Make the text persuasive and premium.
- Keep it concise and marketing-focused.
- Avoid repetition.
',
            ],

            [
                'key' => 'brand_personality',

                'name' => 'Brand Personality Assist',

                'content' => '
You are an elite Arabic branding strategist.

Generate a premium brand personality description.

BUSINESS CONTEXT:
{{business_context}}

BRAND INFO:
{{brand_info}}

USER NOTES:
{{extra_instructions}}

IMPORTANT:
- Write in professional Standard Arabic.
- Make the personality emotionally engaging.
- Keep tone premium and modern.
- Avoid generic wording.
',
            ],

            [
                'key' => 'persona_description',

                'name' => 'Persona Description Assist',

                'content' => '
You are an elite Arabic consumer psychology strategist.

Generate a detailed target audience persona.

BUSINESS CONTEXT:
{{business_context}}

BUSINESS INFO:
{{business_info}}

USER NOTES:
{{extra_instructions}}

IMPORTANT:
- Write in professional Standard Arabic.
- Describe motivations, behavior, lifestyle, and buying psychology.
- Keep output realistic and useful for marketing campaigns.
',
            ],
        ];

        foreach ($templates as $templateData) {

            $template = PromptTemplate::updateOrCreate(
                [
                    'question_key' => $templateData['key'],
                    'type' => 'assist',
                ],
                [
                    'name' => $templateData['name'],
                    'description' => $templateData['name'],
                    'is_active' => true,
                ]
            );

            $version = PromptVersion::create([

                'prompt_template_id' => $template->id,

                'version' => 'v1.0',

                'content' => $templateData['content'],

                'created_by' => 1,

                'is_active' => true,
            ]);

            $template->update([
                'current_version_id' => $version->id,
            ]);
        }
    }
}