<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::create([
            'course_name' => 'arabic classes',
            'description' => 'new',
            'charges' => 5000,
            'madrassa_id' => 1
        ]);
        Course::create([
            'course_name' => 'Islamic Philosophy',
            'description' => 'Philosophy about Islam',
            'charges' => 0,
            'madrassa_id' => 1
        ]);
        Course::create([
            'course_name' => 'Islamic Sheria',
            'description' => 'Foundations of Islamic Sheria',
            'charges' => 0,
            'madrassa_id' => 1
        ]);
        Course::create([
            'course_name' => 'Principles of Hadith',
            'description' => 'Lessons on Principles of Hadith',
            'charges' => 0,
            'madrassa_id' => 1
        ]);
        Course::create([
            'course_name' => 'Islamic History',
            'description' => 'Islamic History at a glance.',
            'charges' => 0,
            'madrassa_id' => 1
        ]);
        Course::create([
            'course_name' => 'Science Of the Quraan',
            'description' => 'An introduction to Science Of the Quraan',
            'charges' => 0,
            'madrassa_id' => 1
        ]);
    }
}
