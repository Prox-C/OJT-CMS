<?php

namespace App\Http\Controllers;

use App\Models\Intern;
use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\InternSetupMail;



class CoordinatorController extends Controller
{
    public function dashboard() {
        return view('coordinator.dashboard');
    }

    public function showInterns()
    {
        // Get the authenticated user's coordinator ID
        $coordinatorId = auth()->user()->coordinator->id;
        
        // Filter interns by the coordinator's ID
        $interns = Intern::with(['user', 'department'])
                    ->where('coordinator_id', $coordinatorId)
                    ->get();

        return view('coordinator.interns', compact('interns'));
    }

    public function newIntern() {
        return view('coordinator.new-intern');
    }

    public function registerIntern(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'student_id' => 'required|string|unique:interns|regex:/^\d{4}-\d{5}$/',
            'birthdate' => 'required|date',
            'year_level' => 'required|integer|between:1,4',
            'section' => 'required|in:a,b,c,d,e,f',
            'academic_year' => 'required|string|regex:/^\d{4}-\d{4}$/',
            'semester' => 'required|in:1st,2nd,midyear',
            'dept_id' => 'required|exists:departments,dept_id'
        ]);

        // Generate temporary password
        $tempPassword = Str::random(16);

        // Create user account with default profile picture
        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($tempPassword),
            'fname' => $validated['first_name'],
            'lname' => $validated['last_name'],
            'contact' => $validated['contact'],
            'birthdate' => $validated['birthdate'],
            'pic' => 'profile_pics/profile.jpg', // Default profile picture
            'temp_password' => true,
            'username' => $validated['student_id']
        ]);

        // Create intern record
        $intern = Intern::create([
            'student_id' => $validated['student_id'],
            'user_id' => $user->id,
            'dept_id' => $validated['dept_id'],
            'coordinator_id' => auth()->user()->coordinator->id, // Set from logged-in coordinator
            'year_level' => $validated['year_level'],
            'section' => $validated['section'],
            'academic_year' => $validated['academic_year'],
            'semester' => $validated['semester'],
            'status' => 'incomplete', 
        ]);

        // Generate activation token
        $token = Str::random(60);
        DB::table('password_setup_tokens')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now()
        ]);

        // Send activation email
        $setupLink = route('password.setup', [
            'token' => $token, 
            'role' => 'intern'
        ]);
        $internName = $validated['first_name'] . ' ' . $validated['last_name'];
        
        Mail::to($user->email)->send(new InternSetupMail(
            $setupLink,
            $internName,
            $tempPassword
        ));

        return redirect()->route('coordinator.interns')
            ->with('success', 'Intern registered successfully. Activation email sent.');
    }

    public function htes() {
        return view('coordinator.htes');
    } 
}
