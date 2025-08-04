<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\InternDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class InternController extends Controller
{
    public function dashboard() {
        if (auth()->user()->intern->first_login) {
            return redirect()->route('intern.skills.select');
        }
        
        return view('student.dashboard');
    }

    public function profile()
    {
        $skills = Skill::where('dept_id', auth()->user()->intern->dept_id)
                    ->orderBy('name')
                    ->get();

        return view('student.profile', compact('skills'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'password' => 'nullable|min:8|confirmed',
        ]);

        try {
            $user = User::findOrFail(auth()->id());
            
            $user->fname = $request->fname;
            $user->lname = $request->lname;
            $user->contact = $request->contact;
            
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            
            if (!$user->save()) {
                throw new \Exception('Failed to save user');
            }

            return back()->with('success', 'Profile updated successfully');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_pic' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $userId = auth()->id();
            $user = DB::table('users')->where('id', $userId)->first();
            
            if (!$user) {
                throw new \Exception('User not found');
            }

            if ($request->hasFile('profile_pic')) {
                // Delete old picture if exists
                if ($user->pic) {
                    Storage::delete($user->pic);
                }

                // Store new picture
                $path = $request->file('profile_pic')->store('profile-pictures', 'public');
                
                // Update database directly
                $updated = DB::table('users')
                            ->where('id', $userId)
                            ->update(['pic' => $path]);
                
                if (!$updated) {
                    throw new \Exception('Failed to update profile picture in database');
                }

                return response()->json([
                    'url' => asset('storage/'.$path),
                    'message' => 'Profile picture updated successfully'
                ]);
            }

            throw new \Exception('No file uploaded');
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    public function updateSkills(Request $request)
    {
        try {
            $request->validate([
                'skills' => 'sometimes|array',
                'skills.*' => 'exists:skills,skill_id',
            ]);

            $intern = auth()->user()->intern;
            
            if (!$intern) {
                throw new \Exception('Intern profile not found');
            }

            $intern->skills()->sync($request->skills ?? []);

            return redirect()->route('intern.profile')
                ->with('success', 'Skills updated successfully');
            
        } catch (\Exception $e) {
            return redirect()->route('intern.profile')
                ->with('error', 'Error updating skills: ' . $e->getMessage());
        }
    }

    public function selectSkills()
    {
        // Get skills matching the intern's department
        $skills = Skill::where('dept_id', auth()->user()->intern->dept_id)
                    ->orderBy('name')
                    ->get();

        return view('student.skills', compact('skills'));
    }

    public function saveSkills(Request $request)
    {
        $request->validate([
            'skills' => 'required|array|min:3',
            'skills.*' => 'exists:skills,skill_id'
        ]);

        DB::transaction(function () use ($request) {
            // Attach selected skills
            auth()->user()->intern->skills()->sync($request->skills);

            // Mark first login as complete
            auth()->user()->intern->update(['first_login' => false]);
        });

        return redirect()->route('intern.dashboard')
                    ->with('success', 'Skills selected successfully!');
    }

    public function documents() {
        $documents = auth()->user()->intern->documents;
        return view('student.documents', compact('documents'));
    }

    public function uploadDocument(Request $request)
    {
        $request->validate([
            'type' => 'required|in:' . implode(',', array_keys(InternDocument::typeLabels())),
            'document' => 'required|file|mimes:pdf|max:5120'
        ]);

        $intern = auth()->user()->intern;
        
        // Delete existing if any
        $intern->documents()->where('type', $request->type)->delete();

        // Store new document
        $path = $request->file('document')->store('intern-documents', 'public');
        
        $intern->documents()->create([
            'type' => $request->type,
            'file_path' => $path,
            'original_name' => $request->file('document')->getClientOriginalName()
        ]);

        return response()->json(['message' => 'Document uploaded successfully']);
    }

    public function deleteDocument(Request $request)
    {
        $document = InternDocument::findOrFail($request->id);
        
        // Verify ownership
        if ($document->intern_id !== auth()->user()->intern->id) {
            abort(403);
        }

        Storage::delete($document->file_path);
        $document->delete();

        return response()->json(['message' => 'Document removed']);
    }
}
