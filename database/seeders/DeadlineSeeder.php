<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Deadline;

class DeadlineSeeder extends Seeder
{
    public function run(): void
    {
        Deadline::create([
            'name' => 'Intern Document Deadline',
            'deadline' => null
        ]);

        Deadline::create([
            'name' => 'Deployment Deadline',
            'deadline' => null
        ]);
    }
}