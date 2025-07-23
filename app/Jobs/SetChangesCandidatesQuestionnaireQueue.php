<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SetChangesCandidatesQuestionnaireQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected array $attributes
    )
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::connection('pm-log')
            ->table('candidate_profiles_has')
            ->insert([
                'profile_key' => $this->attributes['key'],
                'title' => 'Новая анкета',
                'is_visible' => false,
                'meta_attributes' => $this->attributes,
            ]);
    }
}
