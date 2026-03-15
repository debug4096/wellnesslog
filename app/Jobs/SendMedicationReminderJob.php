<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\WithoutRelations;
use Illuminate\Support\Facades\Log;

class SendMedicationReminderJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public array $backoff = [10, 30, 60];

    public function __construct(
        #[WithoutRelations]
        private readonly User $user,
    ) {}

    public function handle(): void
    {
        $medications = $this->user->medications()
            ->where('reminder_time', now()->format('H:i'))
            ->get();

        foreach ($medications as $medication) {
            Log::info('Medication reminder sent', [
                'user_id'       => $this->user->id,
                'user_name'     => $this->user->name,
                'medication'    => $medication->name,
                'reminder_time' => $medication->reminder_time,
            ]);
        }
    }
}
