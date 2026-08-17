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
}