<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Http\Services\GeneticAlgorithmServices\TimetableGA;

class GenerateTimetableJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $timetable;
    public $timeout = 36000;

    public function __construct($timetable)
    {
        $this->timetable = $timetable;
    }

    public function handle()
    {
        try {
            $timetableGA = new TimetableGA($this->timetable);
            $timetable = $timetableGA->run();

            // Dispatch the TimetableGenerated event
            // event(new TimetableGenerated());
        } catch (\Exception $e) {
            // If an error occurred, execute this code
            $this->failed();
        }

        // If the method succeeded, execute this code
        $this->completed($timetable);
    }

    protected function completed($timetable)
    {
        // Run a different job
        // dispatch(new SomeOtherJob($schedule));
        print "Run teacher loading";
        dispatch(new AssignTeacherLoadingJob($timetable));
    }

    protected function failed()
    {
        // Run something else because the original job failed
        $this->timetable->update([
            'status' => 'FAILED',
        ]);
    }
}
