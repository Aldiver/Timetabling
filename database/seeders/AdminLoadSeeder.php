<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminLoadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminloads = [

                [
                  "name" => "Reading Coordinator"
                ],
                [
                  "name" => "LIBRARIAN"
                ],
                [
                  "name" => "SPFL Coordinator"
                ],
                [
                  "name" => "ASST.  SPA"
                ],
                [
                  "name" => "Planning officer"
                ],
                [
                  "name" => "SBM coordinator"
                ],
                [
                  "name" => "Registrar"
                ],
                [
                  "name" => "SCHOOL PAPER ADVISER"
                ],
                [
                  "name" => "Research Coordinator"
                ],
                [
                  "name" => "TASK FORCE COVID MARSHALL"
                ],
                [
                  "name" => "G9 -Guidance Counselor"
                ],
                [
                  "name" => "district reading"
                ],
                [
                  "name" => "School officer"
                ],
                [
                  "name" => "Project WATCH Coordinator"
                ],
                [
                  "name" => "ADMIN. OFFICE CHIEF OF STAFF"
                ],
                [
                  "name" => "CURRICULUM MENTOR"
                ],
                [
                  "name" => "EARTH SCIENCE LABORATORY IN-CHARGE"
                ],
                [
                  "name" => "SCHOOL NURSE"
                ],
                [
                  "name" => "CURRICULUM MENTOR"
                ],
                [
                  "name" => "BIOLOGY LABORATORY IN-CHARGE"
                ],
                [
                  "name" => "LIS COORDINATOR"
                ],
                [
                  "name" => "CHEMISTRY LABORATORY IN-CHARGE"
                ],
                [
                  "name" => "OHSP COORDINATOR"
                ],
                [
                  "name" => "ASST. LIS COORDINATOR"
                ],
                [
                  "name" => "NON-ADVISER"
                ],
                [
                  "name" => "SCHOOL REGISTRAR"
                ],
                [
                  "name" => "PHYSICS LABORATORY IN-CHARGE"
                ],
                [
                  "name" => "CURRICULUM MENTOR"
                ],
                [
                  "name" => "G7-GUIDANCE COUNSELOR"
                ],
                [
                  "name" => "SLM IN-CHARGE"
                ],
                [
                  "name" => "CURRICULUM MENTOR"
                ],
                [
                  "name" => "SCHOOL DISCIPLINARIAN"
                ],
                [
                  "name" => "MATHEMATICS LEARNING CENTER IN-CHARGE"
                ],
                [
                  "name" => "LSEN TEACHER"
                ],
                [
                  "name" => "THEATER ARTS"
                ],
                [
                  "name" => "G9 CURRICULUM CHAIR"
                ],
                [
                  "name" => "G10 CURRICULUM CHAIR"
                ],
                [
                  "name" => "G7 CURRICULUM CHAIR"
                ],
                [
                  "name" => "SIGA COORDINATOR"
                ],
                [
                  "name" => "SLM PREPARATION & DISTRIBUTION"
                ],
                [
                  "name" => "CLINIC TEACHER"
                ],
                [
                  "name" => "SPORTS FACILLITY"
                ],
                [
                  "name" => "RED CROSS COORDINATOR"
                ],
                [
                  "name" => "BSP COORDINATOR "
                ],
                [
                  "name" => "VIDEOGRAPHER"
                ],
                [
                  "name" => "G8 CURRICULUM CHAIR"
                ],
                [
                  "name" => "DRRM COORDINATOR"
                ],
                [
                  "name" => "CSS"
                ],
                [
                  "name" => "AGRI-CROP PRODUCTION (Male/Female)"
                ],
                [
                  "name" => "BEAUTY CARE"
                ],
                [
                  "name" => "CARE GIVING"
                ],
                [
                  "name" => "FOOD PROCESSING"
                ],
                [
                  "name" => "COOKERY"
                ],
                [
                  "name" => "TECHNICAL DRAFTING"
                ],
                [
                  "name" => "EIM"
                ],
                [
                  "name" => "SMAW"
                ],
                [
                  "name" => "EPAS"
                ],
                [
                  "name" => "CARPENTRY"
                ],
                [
                  "name" => "BEAUTY CARE / REGISTRAR"
                ],
                [
                  "name" => "DRESS MAKING"
                ],
                [
                  "name" => "COOKERY (Female)"
                ],
                [
                  "name" => "ICT -AUTOCAD"
                ],
                [
                  "name" => "PAYROLL IN-CHARGE"
                ],
                [
                  "name" => "LSEN COORDINATOR"
                ],
                [
                  "name" => "G10 -Guidance Counselor"
                ],
                [
                  "name" => "Guidance Counselor"
                ],
                [
                  "name" => "Brigada Eskwela"
                ],
                [
                  "name" => "Peace Coordinator"
                ],
                [
                  "name" => "SCHOOL CAMPUS MINISTRY"
                ],
                [
                  "name" => "4Ps Coordinator"
                ]
        ];

        foreach ($adminloads as $admin) {
            $existing = DB::table('admins')->where('name', $admin['name'])->first();

            if (!$existing) {
                DB::table('admins')->insert($admin);
            }
        }
    }
}
