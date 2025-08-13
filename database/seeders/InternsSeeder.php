<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class InternsSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // Fetch all IT Department skills (15 skills)
        $allSkills = DB::table('skills')->where('dept_id', 1)->pluck('skill_id')->toArray();

        if (count($allSkills) < 15) {
            $this->command->error('Need at least 15 skills for dept_id = 1. Please seed skills first.');
            return;
        }

        // We'll distribute all 15 skills across 5 students (3 skills each)
        $skillsPerStudent = 3;
        $shuffledSkills = collect($allSkills)->shuffle();

        for ($i = 0; $i < 5; $i++) {
            // Create user
            $userId = DB::table('users')->insertGetId([
                'email' => 'it.intern' . ($i + 1) . '@example.com',
                'password' => Hash::make('password123'),
                'fname' => 'IT',
                'lname' => 'Student' . ($i + 1),
                'contact' => '09' . rand(100000000, 999999999),
                'pic' => 'profile-pictures/profile.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Create intern - only pending or incomplete status
            $internId = DB::table('interns')->insertGetId([
                'student_id' => '2024-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'user_id' => $userId,
                'dept_id' => 1, // IT Department
                'coordinator_id' => 1,
                'birthdate' => Carbon::create(rand(2000, 2003), rand(1, 12), rand(1, 28)),
                'section' => ['a','b','c','d','e','f'][array_rand(['a','b','c','d','e','f'])],
                'year_level' => rand(3, 4), // 3rd or 4th year
                'academic_year' => '2024–2025',
                'semester' => '2nd',
                'status' => ['incomplete', 'pending'][array_rand(['incomplete', 'pending'])], // Only these statuses
                'first_login' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Assign unique set of 3 skills to each student
            $studentSkills = $shuffledSkills->splice(0, $skillsPerStudent);
            foreach ($studentSkills as $skillId) {
                DB::table('student_skill')->insert([
                    'intern_id' => $internId,
                    'skills_id' => $skillId, // Fixed column name (was skills_id)
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $this->command->info('5 IT interns with unique skill sets (pending/incomplete only) have been seeded.');
    }
}