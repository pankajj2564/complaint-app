<?php use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Auth\StudentOtpController;
use App\Http\Controllers\ComplaintController; // <-- Add this import
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

// ==========================================
// 1. Student OTP Login Routes (Public)
// ==========================================
Route::get('/student/login', [StudentOtpController::class, 'showLoginForm'])->name('student.login');
Route::post('/student/send-otp', [StudentOtpController::class, 'sendOtp'])->name('student.send.otp');
Route::get('/student/verify-otp', [StudentOtpController::class, 'showVerifyForm'])->name('student.verify.otp.form');
Route::post('/student/verify-otp', [StudentOtpController::class, 'verifyOtp'])->name('student.verify.otp');


// ==========================================
// 2. Admin Routes (Only accessible by Admin)
// ==========================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // CSV Import Routes
    Route::get('/admin/import', [ImportController::class, 'showImportForm'])->name('admin.import');
    Route::post('/admin/import', [ImportController::class, 'importUsers'])->name('admin.import.process');
});


// ==========================================
// 3. Employee Dashboard Routes
// ==========================================
Route::middleware(['auth', 'role:employee'])->group(function () {
    Route::get('/employee/dashboard', function () {
        return view('employee.dashboard');
    })->name('employee.dashboard');
});


// ==========================================
// 4. Student Dashboard & Complaint Routes
// ==========================================
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Complaint Routes for Students
    Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    
    // Dynamic Sub-category AJAX endpoint
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

require __DIR__.'/auth.php';