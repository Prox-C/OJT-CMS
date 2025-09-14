<?php

namespace App\Http\Controllers;

use import;
use App\Models\Hte;

use App\Models\User;

use App\Models\Intern;
use App\Mail\HteSetupMail;
use App\Models\InternsHte;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\InternSetupMail;
use App\Imports\InternsImport;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

    class CoordinatorController extends Controller
    {
    public function dashboard() {
        // Get the currently logged-in coordinator
        $coordinator = auth()->user()->coordinator;
        
        // Count students added by this coordinator
        $myStudentsCount = Intern::where('coordinator_id', $coordinator->id)->count();
        $totalHtesCount = Hte::count();
        
        return view('coordinator.dashboard', [
            'myStudentsCount' => $myStudentsCount,
            'totalHtesCount' => $totalHtesCount
        ]);
    }

    // Intern Methods
    public function showInterns()
    {
        // Get the authenticated user's coordinator ID
        $coordinatorId = auth()->user()->coordinator->id;
        
        // Filter interns by the coordinator's ID, ordered by newest first
        $interns = Intern::with(['user', 'department'])
                    ->where('coordinator_id', $coordinatorId)
                    ->orderBy('created_at', 'desc')
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
            'pic' => 'profile-pictures/profile.jpg', // Default profile picture
            'temp_password' => true,
            'username' => $validated['student_id']
        ]);

        // Create intern record
        $intern = Intern::create([
            'student_id' => $validated['student_id'],
            'user_id' => $user->id,
            'dept_id' => $validated['dept_id'],
            'birthdate' => $validated['birthdate'],
            'coordinator_id' => auth()->user()->coordinator->id, // Set from logged-in coordinator
            'year_level' => $validated['year_level'],
            'section' => $validated['section'],
            'academic_year' => $validated['academic_year'],
            'semester' => $validated['semester'],
            'status' => 'pending requirements' // Default status, 
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

    public function showIntern($id)
    {
        $intern = Intern::with(['user', 'department', 'skills', 'coordinator.user'])
            ->findOrFail($id);
        
        return view('coordinator.intern_show', compact('intern'));
    }

    public function editIntern($id)
    {
        // Get the intern with related user data
        $intern = Intern::with('user')->findOrFail($id);
        
        // Check if the coordinator has permission to edit this intern
        if (auth()->user()->coordinator->id !== $intern->coordinator_id) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('coordinator.interns-edit', compact('intern'));
    }

    public function updateIntern(Request $request, $id)
    {
        // Find the intern
        $intern = Intern::findOrFail($id);
        
        // Check if the coordinator has permission to edit this intern
        if (auth()->user()->coordinator->id !== $intern->coordinator_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Validation rules (same as registration)
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'sex' => 'required|in:male,female',
            'email' => 'required|email|unique:users,email,' . $intern->user_id,
            'contact' => 'required|string|max:20',
            'student_id' => 'required|regex:/^\d{4}-\d{5}$/|unique:interns,student_id,' . $id,
            'academic_year' => 'required|regex:/^\d{4}-\d{4}$/',
            'semester' => 'required|in:1st,2nd,midyear',
            'year_level' => 'required|integer|between:1,4',
            'section' => 'required|in:a,b,c,d,e,f',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Update user information
            $user = User::findOrFail($intern->user_id);
            $user->update([
                'fname' => $validated['first_name'],
                'lname' => $validated['last_name'],
                'email' => $validated['email'],
                'contact' => $validated['contact'],
            ]);
            
            // Update intern information
            $intern->update([
                'student_id' => $validated['student_id'],
                'birthdate' => $validated['birthdate'],
                'sex' => $validated['sex'],
                'academic_year' => $validated['academic_year'],
                'semester' => $validated['semester'],
                'year_level' => $validated['year_level'],
                'section' => $validated['section'],
            ]);
            
            DB::commit();
            
            return redirect()->route('coordinator.interns')->with('success', 'Intern updated successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update intern: ' . $e->getMessage());
        }
    }

    public function destroyIntern($id)
    {
        try {
            DB::beginTransaction();

            $intern = Intern::findOrFail($id);
            $userId = $intern->user_id;
            
            $intern->delete();

            // Check if user exists in other tables
            $userStillHasRoles = DB::table('admins')->where('user_id', $userId)->exists()
                || DB::table('coordinators')->where('user_id', $userId)->exists()
                || DB::table('htes')->where('user_id', $userId)->exists();

            if (!$userStillHasRoles) {
                User::destroy($userId);
            }

            DB::commit();

            return redirect()->route('coordinator.interns')
                ->with('success', 'Intern deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('coordinator.interns')
                ->with('error', 'Failed to delete intern: ' . $e->getMessage());
        }
    }

    

    // HTE Methods
    public function htes() {
        $htes = Hte::all(); // Get all HTEs regardless of status
        return view('coordinator.htes', compact('htes'));
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

    public function showHTE($id)
    {
        $hte = Hte::with(['user', 'skills', 'skills.department'])
            ->findOrFail($id);

        // Load interns endorsed for this HTE
        $endorsedInterns = \App\Models\InternsHte::with('intern.department')
            ->where('hte_id', $id)
            ->get();

        $canManage = auth()->user()->coordinator->can_add_hte == 1;

        return view('coordinator.hte_show', compact('hte', 'canManage', 'endorsedInterns'));
    }

    public function toggleMoaStatus($id)
    {
        $hte = Hte::findOrFail($id);
        
        // Toggle the MOA status
        $hte->moa_is_signed = $hte->moa_is_signed === 'yes' ? 'no' : 'yes';
        $hte->save();
        
        return response()->json([
            'success' => true,
            'new_status' => $hte->moa_is_signed,
            'message' => 'MOA status updated successfully'
        ]);
    }

    public function editHte($id)
    {
        // Get the HTE with related user data
        $hte = Hte::with('user')->findOrFail($id);
        
        return view('coordinator.htes-edit', compact('hte'));
    }

    public function updateHte(Request $request, $id)
    {
        // Find the HTE
        $hte = Hte::findOrFail($id);
        
        // Validation rules (same as registration)
        $validated = $request->validate([
            'contact_first_name' => 'required|string|max:255',
            'contact_last_name' => 'required|string|max:255',
            'contact_email' => 'required|email|unique:users,email,' . $hte->user_id,
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'organization_name' => 'required|string|max:255',
            'organization_type' => 'required|in:private,government,ngo,educational,other',
            'hte_status' => 'required|in:active,new',
            'description' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Update user information
            $user = User::findOrFail($hte->user_id);
            $user->update([
                'fname' => $validated['contact_first_name'],
                'lname' => $validated['contact_last_name'],
                'email' => $validated['contact_email'],
                'contact' => $validated['contact_number'],
            ]);
            
            // Update HTE information
            $hte->update([
                'organization_name' => $validated['organization_name'],
                'type' => $validated['organization_type'],
                'status' => $validated['hte_status'],
                'address' => $validated['address'],
                'description' => $validated['description'] ?? null,
            ]);
            
            DB::commit();
            
            return redirect()->route('coordinator.htes')->with('success', 'HTE updated successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update HTE: ' . $e->getMessage());
        }
    }

    public function destroyHTE($id)
    {
        try {
            DB::beginTransaction();

            // Find the HTE
            $hte = HTE::findOrFail($id);

            // Store user ID for later check
            $userId = $hte->user_id;

            // Delete the HTE record (cascade will handle related records like hte_skill)
            $hte->delete();

            // Check if user has other roles
            $hasOtherRoles = DB::table('admins')
                ->where('user_id', $userId)
                ->orWhereExists(function ($query) use ($userId) {
                    $query->select(DB::raw(1))
                          ->from('coordinators')
                          ->where('user_id', $userId);
                })
                ->orWhereExists(function ($query) use ($userId) {
                    $query->select(DB::raw(1))
                          ->from('interns')
                          ->where('user_id', $userId);
                })
                ->exists();

            // Delete user only if they don't have other roles
            if (!$hasOtherRoles) {
                User::where('id', $userId)->delete();
            }

            DB::commit();

            return redirect()->route('coordinator.htes')
                ->with('success', 'HTE account unregistered successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('HTE not found: ' . $e->getMessage());
            return redirect()->route('coordinator.htes')
                ->with('error', 'HTE account not found.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting HTE: ' . $e->getMessage());
            return redirect()->route('coordinator.htes')
                ->with('error', 'An error occurred while unregistering the HTE: ' . $e->getMessage());
        }
    }

    public function removeEndorsement($id)
    {
        $endorsement = \App\Models\InternsHte::findOrFail($id);

        // Optional: check if current user can manage this HTE
        $canManage = auth()->user()->coordinator->can_add_hte == 1;
        if (!$canManage) {
            abort(403, 'Unauthorized');
        }

        // Update intern status back to "ready for deployment"
        $intern = $endorsement->intern;
        if ($intern) {
            $intern->status = 'ready for deployment';
            $intern->save();
        }

        // Delete the endorsement record
        $endorsement->delete();

        return redirect()->back()->with('success', 'Intern endorsement removed successfully.');
    }

    public function showImportForm()
    {
        return view('coordinator.interns.import');
    }

    public function importInterns(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv',
            'coordinator_id' => 'required|exists:coordinators,id',
            'dept_id' => 'required|exists:departments,dept_id'
        ]);

        try {
            $import = new InternsImport(
                $request->coordinator_id,
                $request->dept_id
            );
            
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('import_file'));
            
            return response()->json([
                'success' => true,
                'success_count' => $import->getSuccessCount(),
                'fail_count' => $import->getFailCount(),
                'failures' => $import->getFailures()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error during import: ' . $e->getMessage(),
                'error_details' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function deploy() {
        $htes = \App\Models\HTE::with('skills')
            ->where('moa_is_signed', 'yes')
            ->withCount('internsHte') // assumes relation internsHte() defined in HTE model
            ->havingRaw('slots > interns_hte_count')
            ->get();

        return view('coordinator.deploy', compact('htes'));
    }


    public function getRecommendedInterns(Request $request) {
        $hteId = $request->input('hte_id');
        $requiredSkillIds = $request->input('required_skills', []);
        
        // Get all interns with their skills
        $interns = Intern::with(['user', 'department', 'skills'])
            ->where('status', '!=', 'endorsed') // Filter out already endorsed interns if needed
            ->get();
        
        // Calculate skill matches for each intern
        $internsWithMatches = $interns->map(function($intern) use ($requiredSkillIds) {
            $internSkills = $intern->skills->pluck('skill_id')->toArray();
            
            // Find matching skills
            $matchingSkills = array_intersect($internSkills, $requiredSkillIds);
            
            // Calculate match percentage
            $matchPercentage = count($requiredSkillIds) > 0 
                ? round((count($matchingSkills) / count($requiredSkillIds)) * 100) : 0;
            
            // Get skill names for display
            $matchingSkillNames = $intern->skills
                ->whereIn('skill_id', $matchingSkills)
                ->pluck('name')
                ->toArray();
            
            return [
                'id' => $intern->id,
                'student_id' => $intern->student_id,
                'fname' => $intern->user->fname,
                'lname' => $intern->user->lname,
                'department' => $intern->department->short_name,
                'status' => $intern->status,
                'matching_skills' => $matchingSkillNames,
                'match_percentage' => $matchPercentage,
                'total_matches' => count($matchingSkills)
            ];
        });
        
        // Sort by match percentage (descending) and then by total matches (descending)
        $sortedInterns = $internsWithMatches->sortByDesc(function($intern) {
            return [$intern['match_percentage'], $intern['total_matches']];
        })->values()->all();
        
        return response()->json([
            'success' => true,
            'interns' => $sortedInterns
        ]);
    }

    public function getEndorsedCount(Request $request)
    {
        $count = \App\Models\InternsHte::where('hte_id', $request->hte_id)->count();
        return response()->json(['count' => $count]);
    }

    public function batchEndorseInterns(Request $request)
    {
        $request->validate([
            'hte_id' => 'required|exists:htes,id',
            'intern_ids' => 'required|array|min:1',
            'intern_ids.*' => 'exists:interns,id',
        ]);
        $hteId = $request->hte_id;
        $internIds = $request->intern_ids;
        // Filter interns that are "ready for deployment" and not already endorsed for this HTE
        $readyInterns = \App\Models\Intern::whereIn('id', $internIds)
            ->where('status', 'ready for deployment')
            ->get();
        if ($readyInterns->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No interns are eligible for endorsement.'
            ], 422);
        }
        DB::beginTransaction();
        try {
            foreach ($readyInterns as $intern) {
                // Check if already endorsed for this HTE
                $exists = InternsHte::where('intern_id', $intern->id)
                    ->where('hte_id', $hteId)
                    ->exists();
                if (!$exists) {
                    InternsHte::create([
                        'intern_id' => $intern->id,
                        'hte_id' => $hteId,
                        'status' => 'endorsed',
                        'endorsed_at' => now(),
                    ]);
                    // Update intern status to 'endorsed'
                    $intern->update(['status' => 'endorsed']);
                }
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Selected interns have been successfully endorsed.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while endorsing interns.'
            ], 500);
        }
    }
}
