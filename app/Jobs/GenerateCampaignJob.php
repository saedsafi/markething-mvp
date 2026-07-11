<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Services\AI\CampaignGenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;

    public function __construct(
        public Campaign $campaign
    ) {}

    public function handle(
        CampaignGenerationService $generator
    ): void {
        try {

            $generator->generate($this->campaign);

            $this->campaign->update([
                'status' => 'generated',
            ]);

        } catch (\Throwable $e) {

            report($e);

            $this->campaign->update([
                'status' => 'failed',
            ]);

            throw $e;
        }
    }
}