<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            [
                'name' => 'ENGLISH DEPARTMENT',
                'created_at' => \DB::raw('CURRENT_TIMESTAMP'),
                'updated_at' => \DB::raw('CURRENT_TIMESTAMP')
            ],
            [
                'name' => 'SCIENCE DEPARTMENT',
                'created_at' => \DB::raw('CURRENT_TIMESTAMP'),
                'updated_at' => \DB::raw('CURRENT_TIMESTAMP')
            ],
            [
                'name' => 'MATHEMATICS DEPARTMENT',
                'created_at' => \DB::raw('CURRENT_TIMESTAMP'),
                'updated_at' => \DB::raw('CURRENT_TIMESTAMP')
            ],
            [
                'name' => 'FILIPINO DEPARTMENT',
                'created_at' => \DB::raw('CURRENT_TIMESTAMP'),
                'updated_at' => \DB::raw('CURRENT_TIMESTAMP')
            ],
            [
                'name' => 'MAPEH DEPARTMENT',
                'created_at' => \DB::raw('CURRENT_TIMESTAMP'),
                'updated_at' => \DB::raw('CURRENT_TIMESTAMP')
            ],
            [
                'name' => 'ARPAN DEPARTMENT',
                'created_at' => \DB::raw('CURRENT_TIMESTAMP'),
                'updated_at' => \DB::raw('CURRENT_TIMESTAMP')
            ],
            [
                'name' => 'TLE DEPARTMENT',
                'created_at' => \DB::raw('CURRENT_TIMESTAMP'),
                'updated_at' => \DB::raw('CURRENT_TIMESTAMP')
            ],
            [
                'name' => 'EDUKASYON SA PAGPAPAHALAGA DEPARTMENT',
                'created_at' => \DB::raw('CURRENT_TIMESTAMP'),
                'updated_at' => \DB::raw('CURRENT_TIMESTAMP')
            ],
        ];

        foreach ($departments as $department) {
            $existing = DB::table('departments')->where('name', $department['name'])->first();

            if (!$existing) {
                DB::table('departments')->insert($department);
            }
        }
    }
}
