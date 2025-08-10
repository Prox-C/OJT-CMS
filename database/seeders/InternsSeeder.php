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

        // Fetch IT Department skills
        $skills = DB::table('skills')->where('dept_id', 1)->pluck('skill_id')->toArray();

        if (empty($skills)) {
            $this->command->error('No skills found for dept_id = 1. Please seed skills first.');
            return;
        }

        for ($i = 0; $i < 20; $i++) {
            // Create user
            $userId = DB::table('users')->insertGetId([
                'email' => 'intern' . ($i + 1) . '@example.com',
                'password' => Hash::make('password123'),
                'fname' => 'Intern' . ($i + 1),
                'lname' => 'Lastname' . ($i + 1),
                'contact' => '09' . rand(100000000, 999999999),
                'pic' => 'profile-pictures/profile.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Create intern
            $internId = DB::table('interns')->insertGetId([
                'student_id' => '2021-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'user_id' => $userId,
                'dept_id' => 1,
                'coordinator_id' => 1,
                'birthdate' => Carbon::create(rand(2000, 2004), rand(1, 12), rand(1, 28)),
                'section' => ['a','b','c','d','e','f'][array_rand(['a','b','c','d','e','f'])],
                'year_level' => 4,
                'academic_year' => '2024–2025',
                'semester' => '2nd',
                'status' => ['incomplete', 'pending', 'endorsed'][array_rand(['incomplete', 'pending', 'endorsed'])],
                'first_login' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Assign 1–3 random skills to intern
            $assignedSkills = collect($skills)->shuffle()->take(rand(1, 3))->toArray();
            foreach ($assignedSkills as $skillId) {
                DB::table('student_skill')->insert([
                    'intern_id' => $internId,
                    'skills_id' => $skillId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $this->command->info('20 IT interns with random skills have been seeded.');
    }
}
