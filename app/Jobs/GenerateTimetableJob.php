<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Timetable;

use App\Http\Services\GeneticAlgorithmServices\TimetableGA;

class GenerateTimetableJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $timetable;
    public $timeout = 36000;

    public function __construct(Timetable $timetable)
    {
        $this->timetable = $timetable;
    }

    public function handle()
    {
        try {
            $timetableGA = new TimetableGA($this->timetable);
            // $this->fail(new \Exception('The timetable generation failed.'));
            $timetable = $timetableGA->run();
        } catch (\Exception $e) {
            // If an error occurred, execute this code
            $this->failedJob($e);
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

    protected function failedJob(\Exception $exception)
    {
        print "failed here";
        // Run something else because the original job failed
        $this->timetable->update([
            'status' => 'FAILED',
        ]);
    }
}
