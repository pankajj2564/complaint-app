<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Complaint;
use App\Mail\ComplaintStatusUpdatedMail;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $complaints = Complaint::where('assigned_to', Auth::id())
        ->with('user', 'category')
        ->latest()
        ->paginate(10);
            
        return view('employee.dashboard', compact('complaints'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,closed'
        ]);

        $complaint = Complaint::where('id', $id)
            ->where('assigned_to', Auth::id())
            ->firstOrFail();
        
        $complaint->status = $request->status;
        $complaint->save();

        // Send Email Notification to Complainant
        try {
            Mail::to($complaint->user->email)->send(new ComplaintStatusUpdatedMail($complaint));
        } catch (\Exception $e) {
            \Log::error('Status mail error: ' . $e->getMessage());
        }

        return back()->with('success', 'Complaint status updated successfully!');
    }
}