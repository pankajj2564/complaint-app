<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\EmployeeProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    // Show Import View
    public function showImportForm()
    {
        return view('admin.import');
    }

    // Process CSV Import
    public function importUsers(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
            'role' => 'required|in:student,employee'
        ]);

        $file = $request->file('file');
        $role = $request->input('role');

        $handle = fopen($file->getRealPath(), 'r');
        $header = true;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if ($header) {
                    $header = false;
                    continue;
                }

                $name = $row[1] ?? '';
                $email = $row[2] ?? '';
                $phone = $row[3] ?? '';
                $password = Hash::make('password123'); // Default password

                if (!empty($email)) {
                    // 1. Create or Update Base User
                    $user = User::updateOrCreate(
                        ['email' => $email],
                        [
                            'name' => $name,
                            'password' => $password,
                            'role' => $role,
                            'status' => 'active'
                        ]
                    );

                    // 2. Insert/Update Student Profile
                    if ($role == 'student') {
                        StudentProfile::updateOrCreate(
                            ['user_id' => $user->id],
                            [
                                'phone_number' => $phone,
                                'roll_number' => $row[4] ?? null,
                                'gr_number' => $row[5] ?? null,
                                'course' => $row[6] ?? null,
                                'school' => $row[7] ?? null,
                            ]
                        );
                    } 
                    // 3. Insert/Update Employee Profile
                    elseif ($role == 'employee') {
                        EmployeeProfile::updateOrCreate(
                            ['user_id' => $user->id],
                            [
                                'phone_number' => $phone,
                                'employee_code' => $row[4] ?? null,                                
                                'designation' => $row[5] ?? null,
                                'department' => $row[6] ?? null,
                            ]
                        );
                    }
                }
            }
            fclose($handle);
            DB::commit();

            return back()->with('success', 'Users and profiles imported successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }
}