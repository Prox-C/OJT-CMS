<?php

namespace App\Http\Controllers;

use App\Models\Hte;
use App\Models\User;

use App\Models\Intern;

use App\Mail\HteSetupMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\InternSetupMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;



class CoordinatorController extends Controller
{
    public function dashboard() {
        return view('coordinator.dashboard');
    }

    // Intern Methods
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
            'pic' => 'profile-pictures/profile.jpg', // Default profile picture
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

    // HTE Methods
    public function htes() {
        return view('coordinator.htes');
    } 

    public function newHTE() {
        return view('coordinator.new-hte');
    }

    public function registerHTE(Request $request)
    {
        $validated = $request->validate([
            'contact_email' => 'required|email|unique:users,email',
            'contact_first_name' => 'required|string|max:255',
            'contact_last_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'organization_name' => 'required|string|max:255',
            'organization_type' => 'required|in:private,government,ngo,educational,other',
            'hte_status' => 'required|in:active,new',
            'description' => 'nullable|string',
            'coordinator_id' => 'required|exists:coordinators,id'
        ]);

        // Generate temporary password
        $tempPassword = Str::random(16);

        // Create user account with default profile picture
        $user = User::create([
            'email' => $validated['contact_email'],
            'password' => Hash::make($tempPassword),
            'fname' => $validated['contact_first_name'],
            'lname' => $validated['contact_last_name'],
            'contact' => $validated['contact_number'],
            'pic' => 'profile-pictures/profile.jpg', // Default profile picture
            'temp_password' => true,
            'username' => $validated['contact_email'] // HTEs use email as username
        ]);

        // Create HTE record
        $hte = Hte::create([
            'user_id' => $user->id,
            'status' => $validated['hte_status'],
            'type' => $validated['organization_type'],
            'address' => $validated['address'],
            'description' => $validated['description'],
            'organization_name' => $validated['organization_name'],
            'slots' => 0, // Initial slots can be 0, can be updated later
            'moa_path' => null // Will be set after MOA is uploaded
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
            'role' => 'hte' // New role for HTE
        ]);
        $contactName = $validated['contact_first_name'] . ' ' . $validated['contact_last_name'];
        
        // Check if we need to attach MOA (for new HTEs)
        $moaAttachmentPath = null;
        if ($validated['hte_status'] === 'new') {
            $moaAttachmentPath = storage_path('app/public/moa-templates/default-moa.pdf');
        }

        Mail::to($user->email)->send(new HteSetupMail(
            $setupLink,
            $contactName,
            $validated['organization_name'],
            $tempPassword,
            $moaAttachmentPath,
            $user->email // Add the email address here
        ));

        return redirect()->route('coordinator.htes')
            ->with('success', 'HTE registered successfully. Activation email sent.');
    }
}
