<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $days = [
            [
                'name' => 'Monday',
                'short_name' => 'Mon',
                'rank' => 1
            ],
            [
                'name' => 'Tuesday',
                'short_name' => 'Tue',
                'rank' => 2
            ],
            [
                'name' => 'Wednesday',
                'short_name' => 'Wed',
                'rank' => 3
            ],
            [
                'name' => 'Thursday',
                'short_name' => 'Thur',
                'rank' => 4
            ],
            [
                'name' => 'Friday',
                'short_name' => 'Fri',
                'rank' => 5
            ],
            [
                'name' => 'Saturday',
                'short_name' => 'Sat',
                'rank' => 6
            ],
            [
                'name' => 'Sunday',
                'short_name' => 'Sun',
                'rank' => 7
            ]
        ];

        foreach ($days as $day) {
            $existing = DB::table('classdays')->where('name', $day['name'])->first();

            if (!$existing) {
                DB::table('classdays')->insert($day);
            }
        }
    }
}
