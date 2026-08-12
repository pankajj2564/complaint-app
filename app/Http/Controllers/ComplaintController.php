<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Complaint;

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

        Complaint::create([
            'ticket_number' => 'TCK-' . strtoupper(Str::random(6)),
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'location' => $request->location,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Complaint ticket submitted successfully!');
    }
}