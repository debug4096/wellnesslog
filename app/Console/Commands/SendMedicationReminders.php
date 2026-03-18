<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\SendMedicationReminderJob;
use App\Models\User;
use Illuminate\Console\Command;

class SendMedicationReminders extends Command
{
    protected $signature = 'app:send-medication-reminders';

    protected $description = 'Dispatch medication reminder jobs';

    public function handle(): void
    {
        $currentTime = now()->format('H:i');
        $count = 0;

        User::whereHas('medications', function ($query) use ($currentTime) {
            $query->where('reminder_time', $currentTime);
        })->chunk(100, function ($users) use (&$count, $currentTime) {
            foreach ($users as $user) {
                SendMedicationReminderJob::dispatch($user, $currentTime);
                $count++;
            }
        });

        $this->info("{$count} medication reminders sent.");
    }
}
