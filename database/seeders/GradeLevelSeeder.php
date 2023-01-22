<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gradelevels = [
            [
                'level' => 7,
            ],
            [
                'level' => 8,
            ],
            [
                'level' => 9,
            ],
            [
                'level' => 10,
            ]
        ];

        foreach ($gradelevels as $gradelevel) {
            $existing = DB::table('gradelevels')->where('level', $gradelevel['level'])->first();

            if (!$existing) {
                DB::table('gradelevels')->insert($gradelevel);
            }
        }
    }
}
