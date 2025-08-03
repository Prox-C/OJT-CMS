<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;
use Carbon\Carbon;

class SkillSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        
        $skills = [
            // Information Technology (dept_id = 1)
            1 => [
                'Web Development',
                'Frontend Development',
                'Backend Development',
                'Mobile App Development',
                'UI/UX Design',
                'Graphic Design',
                'Database Management',
                'Network Administration',
                'Cybersecurity',
                'Cloud Computing',
                'Data Science',
                'Machine Learning',
                'Artificial Intelligence',
                'Software Testing',
                'DevOps',
            ],
            
            // Civil Engineering (dept_id = 2)
            2 => [
                'Structural Design',
                'AutoCAD',
                'Revit',
                'Construction Management',
                'Surveying',
                'Geotechnical Engineering',
                'Transportation Engineering',
                'Environmental Engineering',
                'Hydraulics',
                'Project Estimation',
                'Building Codes',
                'Concrete Technology',
                'Steel Design',
                'Road Design',
                'Urban Planning',
            ],
            
            // Electrical Engineering (dept_id = 3)
            3 => [
                'Circuit Design',
                'Power Systems',
                'PLC Programming',
                'Renewable Energy',
                'Electrical Machines',
                'Control Systems',
                'Embedded Systems',
                'Industrial Automation',
                'Power Electronics',
                'Electrical Safety',
                'HVAC Systems',
                'Smart Grid Technology',
                'Instrumentation',
                'PCB Design',
                'Robotics',
            ]
        ];

        foreach ($skills as $deptId => $departmentSkills) {
            foreach ($departmentSkills as $skillName) {
                Skill::create([
                    'dept_id' => $deptId,
                    'name' => $skillName,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}