<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Complaint;
use App\Mail\ComplaintSubmittedMail;
use Illuminate\Support\Facades\Mail;

class ComplaintController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        if ($user->role === 'student') {
            $user->load('studentProfile');
        } elseif ($user->role === 'employee') {
            $user->load('employeeProfile'); // Agar employee profile table hai toh
        }
        //$user = Auth::user()->load('studentProfile');
        $categories = Category::all();

        return view('complaints.create', compact('user', 'categories'));
    }

    // Dynamic AJAX endpoint to fetch sub-categories based on category selection
    public function getSubCategories($categoryId)
    {
        $subCategories = SubCategory::where('category_id', $categoryId)->get();
        return response()->json($subCategories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // 1. Logged-in user ki details lein
        $user = Auth::user();

        // 2. Complaint create karein
        $complaint = Complaint::create([
            'ticket_number' => 'TCK-' . strtoupper(Str::random(6)),
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'location' => $request->location,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        // 3. Department/Support ki 2 Email IDs jahan notification jaani chahiye
        $departmentEmails = [
            'gm.digital@cgcuniversity.in', // Pehli email
            'dgm.digital@cgcuniversity.in'  // Doosri email
        ];

        try {
            // Email 1: Student ko confirmation bhejein
            Mail::to($user->email)->send(new ComplaintSubmittedMail($complaint, $user));

            // Email 2: Support department ki dono IDs par bhejein
            Mail::to($departmentEmails)->send(new ComplaintSubmittedMail($complaint, $departmentEmails));

        } catch (\Exception $e) {
            \Log::error('Complaint Email Error: ' . $e->getMessage());
        }
        if ($user->role == 'student') {
            return redirect()->route('student.dashboard')->with('success', 'Complaint ticket submitted successfully!');
        }
        if ($user->role == 'employee') {
            return redirect()->route('employee.dashboard')->with('success', 'Complaint ticket submitted successfully!');
        }

        return redirect()->route('dashboard')->with('success', 'Complaint ticket submitted successfully!');
        
    }

    public function studentDashboard()
    {
        $user = Auth::user();

        // Sirf logged-in student ki complaints fetch karein
        $data["complaints"] = Complaint::with('category', 'assignedEmployee')
                        ->where('user_id', $user->id)
                        ->latest()
                        ->paginate(10);

        // Kuch metrics/counts (Optional)
        $data["total"] = Complaint::where('user_id', $user->id)->count();
        $data["pending"] = Complaint::where('user_id', $user->id)->where('status', 'pending')->count();
        $data["inProgress"] = Complaint::where('user_id', $user->id)->where('status', 'in-progress')->count();
        $data["resolved"] = Complaint::where('user_id', $user->id)->where('status', 'resolved')->count();
        $data["closed"] = Complaint::where('user_id', $user->id)->where('status', 'closed')->count();
        return view('students.dashboard', compact('data'));
    }

    public function destroyComplaint($id)
    {
        $complaint = Complaint::findOrFail($id);
        $user = Auth::user();

        // Check if the user is an Admin OR the owner of the complaint
        if ($user->role !== 'admin' && $complaint->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Perform the deletion
        $complaint->delete();

        return redirect()->back()->with('success', 'Complaint deleted successfully.');
    }
}