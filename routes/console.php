<?php

declare(strict_types=1);

use App\Console\Commands\SendMedicationReminders;

Schedule::command(SendMedicationReminders::class)->everyMinute();
