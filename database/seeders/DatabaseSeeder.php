<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ClassDaySeeder::class,
            BasicAdminPermissionSeeder::class,
            GradeLevelSeeder::class,
            DepartmentSeeder::class,
            AdminLoadSeeder::class,
            SectionSeeder::class,
            TeacherSeeder::class,
        ]);
    }
}
