<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use App\Events\TimetableGenerated;

use App\Http\Services\GeneticAlgorithmServices\TimetableGA;

class GenerateTimetableJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

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
        //event trigger
        // Dispatch the TimetableGenerated event
        // event(new TimetableGenerated());
    }
}
