<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\User;
use App\Mail\ComplaintAssignedMail;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function dashboard()
    {
        $data["totalComplaints"] = Complaint::count();
        $data["pendingComplaints"] = Complaint::where('status', 'pending')->count();
        $data["inprogressComplaints"] = Complaint::where('status', 'in_progress')->count();
        $data["resolvedComplaints"] = Complaint::where('status', 'resolved')->count();
        $data["closedComplaints"] = Complaint::where('status', 'closed')->count();
        //$assignedComplaints = Complaint::whereNotNull('assigned_to')->count();
        //$resolvedComplaints = Complaint::whereIn('status', ['resolved', 'closed'])->count();
        
        $data["complaints"] = Complaint::with('user', 'assignedEmployee', 'category')->latest()->paginate(10);

        return view('admin.dashboard', compact('data'));
    }

    public function complaints()
    {
        // get() ko paginate(10) se replace karein
        $complaints = Complaint::with('user', 'assignedEmployee', 'category')->latest()->paginate(10); 
        
        $employees = User::where('role', 'employee')->where('status', 'active')->get();

        return view('admin.complaints', compact('complaints', 'employees'));
    }

    public function assignComplaint(Request $request, $id)
    {
        $request->validate(['assigned_to' => 'required|exists:users,id']);

        $complaint = Complaint::findOrFail($id);
        $complaint->assigned_to = $request->assigned_to;
        $complaint->status = 'in_progress';
        $complaint->save();

        try {
            // 1. Email to the User/Student who raised the complaint
            if ($complaint->user && $complaint->user->email) {
                Mail::to($complaint->user->email)->send(new ComplaintAssignedMail($complaint, 'user'));
            }

            // 2. Email to the Employee/Staff to whom it is assigned
            if ($complaint->assignedEmployee && $complaint->assignedEmployee->email) {
                Mail::to($complaint->assignedEmployee->email)->send(new ComplaintAssignedMail($complaint, 'employee'));
            }

        } catch (\Exception $e) {
            \Log::error('Complaint Assignment Email Error: ' . $e->getMessage());
        }

        return back()->with('success', 'Complaint assigned to employee successfully and emails sent!');
    }

    public function students()
    {
        $students = User::where('role', 'student')->with('studentProfile')->get();
        return view('admin.students', compact('students'));
    }

    public function employees()
    {
        $employees = User::where('role', 'employee')->with('employeeProfile')->latest()->paginate(10); 
        return view('admin.employees', compact('employees'));
    }
    public function destroyUser($id)
    {
        // Prevent admin from accidentally deleting their own account
        if (Auth::id() == $id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user = User::findOrFail($id);

        // Optional: Manually delete profiles if database cascade isn't set up
        if ($user->studentProfile) {
            $user->studentProfile()->delete();
        }
        if ($user->employeeProfile) {
            $user->employeeProfile()->delete();
        }

        // Delete the main user
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
    public function showUser($id)
    {
        $user = User::with(['studentProfile', 'employeeProfile'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }
    public function editUser($id)
    {
        $user = User::with(['studentProfile', 'employeeProfile'])->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }
    public function updateUser(Request $request, $id)
    {
        $user = User::with(['studentProfile', 'employeeProfile'])->findOrFail($id);

        // Validate common user fields
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,student,employee',
            'status' => 'required|string',
        ]);

        // Update base user details
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
        ]);

        // Update Profile based on user role
        if ($user->role === 'student' && $user->studentProfile) {
            $request->validate([
                'phone_number' => 'nullable|string|max:20',
                'roll_number' => 'nullable|string|max:50',
                'course' => 'nullable|string|max:100',
            ]);
            $user->studentProfile()->update($request->only([
                'phone_number', 'roll_number', 'gr_number', 'student_type', 'course', 'school'
            ]));
        } elseif ($user->role === 'employee' && $user->employeeProfile) {
            $request->validate([
                'phone_number' => 'nullable|string|max:20',
                'employee_code' => 'nullable|string|max:50',
                'department' => 'nullable|string|max:100',
            ]);
            $user->employeeProfile()->update($request->only([
                'phone_number', 'employee_code', 'department', 'designation'
            ]));
        }

        return redirect()->route('admin.users.show', $user->id)->with('success', 'User updated successfully.');
    }
}