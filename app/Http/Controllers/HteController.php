<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HteController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Check if HTE exists
        if (!$user->hte) {
            abort(403, 'HTE profile not found');
        }
        
        if ($user->hte->first_login === 1) {
            return redirect()->route('hte.first-login.details');
        }
        
        if ($user->hte->first_login === 2) {
            return redirect()->route('hte.first-login.skills');
        }
        
        return view('hte.dashboard');
    }

    public function showDetailsForm()
    {
        $hte = Auth::user()->hte;
        if (!$hte) {
            abort(403, 'HTE profile not found');
        }
        
        return view('hte.first-login-details', compact('hte'));
    }

    public function confirmDetails(Request $request)
    {
        $user = Auth::user();
        $hte = $user->hte;

        if (!$hte) {
            abort(403, 'HTE profile not found');
        }

        $request->validate([
            'contact_first_name' => 'required|string|max:255',
            'contact_last_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'organization_name' => 'required|string|max:255',
            'organization_type' => 'required|in:private,government,ngo,educational,other',
            'slots' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        // Update user details using DB
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'fname' => $request->contact_first_name,
                'lname' => $request->contact_last_name,
                'contact' => $request->contact_number,
            ]);

        // Update HTE details using DB
        DB::table('htes')
            ->where('id', $hte->id)
            ->update([
                'address' => $request->address,
                'organization_name' => $request->organization_name,
                'type' => $request->organization_type,
                'slots' => $request->slots,
                'description' => $request->description,
                'first_login' => 2,
            ]);

        return redirect()->route('hte.first-login.skills');
    }

    public function showSkillsForm()
    {
        // Get all skills grouped by department
        $departments = Department::with('skills')->get();
        
        // Fix the ambiguous column issue by specifying table
        $selectedSkills = DB::table('hte_skill')
                        ->where('hte_id', auth()->user()->hte->id)
                        ->pluck('skill_id')
                        ->toArray();

        return view('hte.first-login-skills', compact('departments', 'selectedSkills'));
    }

public function saveSkills(Request $request)
{
    $request->validate([
        'skills' => 'required|array|min:5',
        'skills.*' => 'exists:skills,skill_id',
    ]);

    try {
        DB::transaction(function () use ($request) {
            $hteId = auth()->user()->hte->id;
            
            DB::table('hte_skill')->where('hte_id', $hteId)->delete();
            
            $skillsData = array_map(function($skillId) use ($hteId) {
                return [
                    'hte_id' => $hteId,
                    'skill_id' => $skillId,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }, $request->skills);
            
            DB::table('hte_skill')->insert($skillsData);
            
            DB::table('htes')
                ->where('id', $hteId)
                ->update(['first_login' => 0]);
        });

        return response()->json([
            'redirect' => route('hte.dashboard'),
            'message' => 'Skills saved successfully!'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error saving skills: ' . $e->getMessage()
        ], 500);
    }
}

}