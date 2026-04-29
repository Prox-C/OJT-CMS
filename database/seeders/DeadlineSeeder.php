<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Deadline;

class DeadlineSeeder extends Seeder
{
    public function run(): void
    {
        Deadline::create([
            'name' => 'Pre-deployment Requirements Deadline',
            'deadline' => null
        ]);

        Deadline::create([
            'name' => 'Submission of Honorarium Requirements Deadline',
            'deadline' => null
        ]);
    }
}