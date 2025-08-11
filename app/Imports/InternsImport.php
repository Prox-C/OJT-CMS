<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Intern;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use App\Mail\InternSetupMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InternsImport implements ToCollection, WithHeadingRow
{
    private $coordinatorId;
    private $deptId;
    private $successCount = 0;
    private $failures = [];

    public function __construct($coordinatorId, $deptId)
    {
        $this->coordinatorId = $coordinatorId;
        $this->deptId = $deptId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $this->processRow($row, $index + 2); // +2 for header row and 1-based index
        }
    }

    private function processRow($row, $rowNumber)
    {
        // Normalize the data first
        $data = $this->normalizeData($row);

        // Validate the row
        $validator = Validator::make($data, $this->validationRules());

        if ($validator->fails()) {
            $this->addFailure($rowNumber, $data, $validator->errors()->all());
            return;
        }

        try {
            DB::beginTransaction();

            $tempPassword = Str::random(16);

            // Create user
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make($tempPassword),
                'fname' => $data['first_name'],
                'lname' => $data['last_name'],
                'contact' => $data['contact'],
                'pic' => 'profile-pictures/profile.jpg',
                'temp_password' => true,
                'username' => $data['email']
            ]);

            // Create intern
            Intern::create([
                'student_id' => $data['student_id'],
                'user_id' => $user->id,
                'dept_id' => $this->deptId,
                'coordinator_id' => $this->coordinatorId,
                'birthdate' => $data['birthdate'],
                'section' => $data['section'],
                'year_level' => $data['year_level'],
                'academic_year' => $data['academic_year'],
                'semester' => $data['semester'],
                'status' => 'incomplete',
                'first_login' => 1
            ]);

            // Generate activation token
            $token = Str::random(60);
            DB::table('password_setup_tokens')->insert([
                'email' => $user->email,
                'token' => $token,
                'created_at' => now()
            ]);

            // Send activation email
            Mail::to($user->email)->send(new InternSetupMail(
                route('password.setup', ['token' => $token, 'role' => 'intern']),
                $data['first_name'] . ' ' . $data['last_name'],
                $tempPassword,
                $data['email']
            ));

            DB::commit();
            $this->successCount++;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addFailure($rowNumber, $data, [$e->getMessage()]);
        }
    }

    private function normalizeData($row)
    {
        return [
            'first_name' => $row['first_name'] ?? $row['firstname'] ?? null,
            'last_name' => $row['last_name'] ?? $row['lastname'] ?? null,
            'email' => $row['email'] ?? null,
            'contact' => $row['contact'] ?? $row['contact_number'] ?? null,
            'student_id' => $row['student_id'] ?? null,
            'birthdate' => $this->parseDate($row['birthdate'] ?? null),
            'year_level' => $row['year_level'] ?? null,
            'section' => strtolower($row['section'] ?? null),
            'academic_year' => $row['academic_year'] ?? null,
            'semester' => strtolower($row['semester'] ?? null),
        ];
    }

    private function parseDate($date)
    {
        if (empty($date)) {
            return null;
        }

        if (is_numeric($date)) {
            // Handle Excel date serial numbers
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d');
        }

        try {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function validationRules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'contact' => 'required|string|max:20',
            'student_id' => [
                'required',
                'string',
                'regex:/^\d{4}-\d{5}$/',
                Rule::unique('interns', 'student_id')
            ],
            'birthdate' => 'required|date',
            'year_level' => 'required|integer|between:1,4',
            'section' => 'required|in:a,b,c,d,e,f',
            'academic_year' => 'required|regex:/^\d{4}-\d{4}$/',
            'semester' => 'required|in:1st,2nd,midyear',
        ];
    }

    private function addFailure($rowNumber, $data, $errors)
    {
        $this->failures[] = [
            'row' => $rowNumber,
            'student_id' => $data['student_id'] ?? 'N/A',
            'name' => ($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''),
            'errors' => $errors
        ];
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getFailCount()
    {
        return count($this->failures);
    }

    public function getFailures()
    {
        return $this->failures;
    }
}