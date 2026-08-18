<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Auth\StudentOtpController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //return redirect('/login');
})->name('home');

// ==========================================
// 1. Unified OTP Login Routes (For both Students & Employees)
// ==========================================
Route::get('/auth/login', [StudentOtpController::class, 'showLoginForm'])->name('student.login'); // Aap chaho toh ise 'auth.login' bhi kar sakte hain
Route::post('/auth/send-otp', [StudentOtpController::class, 'sendOtp'])->name('student.send.otp');
Route::get('/auth/verify-otp', [StudentOtpController::class, 'showVerifyForm'])->name('student.verify.otp.form');
Route::post('/auth/verify-otp', [StudentOtpController::class, 'verifyOtp'])->name('student.verify.otp');


// ==========================================
// 2. Admin Routes (Only accessible by Admin via Password/auth.php)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Complaints Management & Assignment
    Route::get('/complaints', [AdminController::class, 'complaints'])->name('complaints');
    Route::delete('/complaints/delete/{id}', [ComplaintController::class, 'destroyComplaint'])->name('complaints_delete');
    Route::post('/complaints/{id}/assign', [AdminController::class, 'assignComplaint'])->name('complaints.assign');

    // Students & Employees Lists
    Route::get('/students', [AdminController::class, 'students'])->name('students');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');

    Route::get('/employees', [AdminController::class, 'employees'])->name('employees');
    Route::delete('/user/delete/{id}', [AdminController::class, 'destroyUser'])->name('user_delete');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('users.show');
    
    // CSV Import Routes
    Route::get('/import', [ImportController::class, 'showImportForm'])->name('import');
    Route::post('/import', [ImportController::class, 'importUsers'])->name('import.process');
});


// ==========================================
// 3. Employee Dashboard & Action Routes
// ==========================================
Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('dashboard');
    
    // Employee can update status of assigned complaints
    Route::patch('/complaints/{id}/status', [EmployeeController::class, 'updateStatus'])->name('complaints.update');
});
Route::middleware(['auth'])->group(function () {
    // Student Dashboard Route
    Route::get('/student/dashboard', [ComplaintController::class, 'studentDashboard'])->name('student.dashboard');
});

// ==========================================
// 4. Complaint Routes (Accessible by BOTH Students & Employees)
// ==========================================
Route::middleware(['auth'])->group(function () {
    // Check if user is either student or employee to raise complaints
    Route::middleware(['role:student,employee'])->group(function () {
        Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
        Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
        Route::get('/my-complaints', [ComplaintController::class, 'myComplaints'])->name('complaints.my');
    });

    // Dynamic Sub-category AJAX endpoint (Accessible during complaint creation)
    Route::get('/api/sub-categories/{categoryId}', [ComplaintController::class, 'getSubCategories']);
});


// ==========================================
// 5. Profile Management Routes (Common)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Laravel Default Auth Routes (For Admin Password Login)
require __DIR__.'/auth.php';