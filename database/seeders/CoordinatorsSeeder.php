<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Coordinator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class CoordinatorsSeeder extends Seeder
{
    public function run()
    {
        $defaultProfilePic = 'profile_pics/profile.jpg'; // Default image path

        // Coordinator 1 (can add HTE)
        $user1 = User::create([
            'email' => 'bonifacio.salvador@evsu.edu.ph',
            'password' => Hash::make('password123'),
            'fname' => 'Bonifacio',
            'lname' => 'Salvador',
            'contact' => '09507395757',
            'pic' => $defaultProfilePic
        ]);

        Coordinator::create([
            'faculty_id' => '2016-70707',
            'user_id' => $user1->id,
            'dept_id' => 1, // IT
            'can_add_hte' => '0'
        ]);

        // Coordinator 2 (cannot add HTE)
        $user2 = User::create([
            'email' => 'maria.cruz@evsu.edu.ph',
            'password' => Hash::make('password123'),
            'fname' => 'Maria',
            'lname' => 'Cruz',
            'contact' => '09123456789',
            'pic' => $defaultProfilePic
        ]);

        Coordinator::create([
            'faculty_id' => '2018-80808',
            'user_id' => $user2->id,
            'dept_id' => 1, // IT
            'can_add_hte' => '1'
        ]);

        // Coordinator 3 (cannot add HTE)
        $user3 = User::create([
            'email' => 'juan.dela@evsu.edu.ph',
            'password' => Hash::make('password123'),
            'fname' => 'Juan',
            'lname' => 'Dela Cruz',
            'contact' => '09876543210',
            'pic' => $defaultProfilePic
        ]);

        Coordinator::create([
            'faculty_id' => '2019-90909',
            'user_id' => $user3->id,
            'dept_id' => 1, // IT
            'can_add_hte' => '1'
        ]);
    }
}