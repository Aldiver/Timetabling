<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

use App\Http\Services\GeneticAlgorithmServices\TimetableGA;

class GenerateTimetableJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use IsMonitored;

    protected $schoolProgram;
    public $timeout = 36000;

    public function __construct($schoolProgram)
    {
        $this->schoolProgram = $schoolProgram;
    }

    public function handle()
    {
        $timetableGA = new TimetableGA($this->schoolProgram);
        $schedule = $timetableGA->run();
        dd($schedule->getFitness());
        // return redirect()->route('dashboard.index')->with('schedule', $schedule);
    }
}
