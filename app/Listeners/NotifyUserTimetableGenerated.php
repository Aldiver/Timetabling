<?php

namespace App\Listeners;

use App\Events\TimetableGenerated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Inertia\Inertia;

class NotifyTimetableGenerationComplete implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  TimetableGenerated  $event
     * @return void
     */
    public function handle()
    {
        // $timetable = $event->timetable;

        // Show a success notification to the user using Inertia
        Inertia::render('Data/Dashboard/Index', [
            'success' => 'Timetable generated successfully!'
        ]);
    }
}
