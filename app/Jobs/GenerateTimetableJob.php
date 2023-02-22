<?php

namespace App\Jobs;

use App\Http\Services\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class GenerateTimetableJob implements ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use IsMonitored;

    protected $schoolProgram;

    public function __construct($schoolProgram)
    {
        $this->schoolProgram = $schoolProgram;
    }

    public function handle()
    {
        $schedule = new Schedule($this->schoolProgram);
        $scheduleData = $schedule->toArray();
        return $scheduleData;
    }
}
