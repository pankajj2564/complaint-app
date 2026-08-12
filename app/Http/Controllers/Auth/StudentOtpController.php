<?php 

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\VerificationCode;
use App\Models\StudentProfile;
use Carbon\Carbon;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Mail;

class StudentOtpController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.student-login');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['login_identifier' => 'required']);
        $input = $request->input('login_identifier');

        $student = User::where('role', 'student')
            ->where(function($q) use ($input) {
                $q->where('email', $input)
                  ->orWhereHas('studentProfile', function($subQ) use ($input) {
                      $subQ->where('roll_number', $input)
                            ->orWhere('gr_number', $input)
                            ->orWhere('phone_number', $input);
                  });
            })->first();

        if (!$student) {
            return back()->withErrors(['login_identifier' => 'No student account found with this information.']);
        }

        if ($student->status === 'suspended') {
            return back()->withErrors(['login_identifier' => 'Your account has been suspended. Please contact Admin.']);
        }

        $otp = rand(100000, 999999);

        VerificationCode::updateOrCreate(
            ['identifier' => $input],
            [
                'otp' => Hash::make($otp),
                'expires_at' => Carbon::now()->addMinutes(10)
            ]
        );

        // Mail sending with proper error catch
        try {
            Mail::to($student->email)->send(new SendOtpMail($otp));
        } catch (\Exception $e) {
            // 1. Error ko storage/logs/laravel.log me record karein taaki debugging ho sake
            \Log::error('OTP Email Sending Error: ' . $e->getMessage());

            // 2. Agar aap chahte hain ki email fail hone par user ko rok dein:
            // return back()->withErrors(['login_identifier' => 'Failed to send OTP email. Please check configuration or try again.']);
        }

        // Store session and redirect
        session(['student_login_identifier' => $input]);
        session()->flash('debug_otp', $otp); 

        return redirect()->route('student.verify.otp.form');
    }

    public function showVerifyForm()
    {
        if (!session()->has('student_login_identifier')) {
            return redirect()->route('student.login');
        }
        return view('auth.student-verify');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);
        
        $input = session('student_login_identifier');
        $verification = VerificationCode::where('identifier', $input)->first();

        if (!$verification || Carbon::now()->greaterThan($verification->expires_at)) {
            return back()->withErrors(['otp' => 'The OTP has expired or is invalid.']);
        }

        if (!Hash::check($request->otp, $verification->otp)) {
            return back()->withErrors(['otp' => 'Incorrect OTP code entered.']);
        }

        $student = User::where('role', 'student')
            ->where(function($q) use ($input) {
                $q->where('email', $input)
                  ->orWhereHas('studentProfile', function($subQ) use ($input) {
                      $subQ->where('roll_number', $input)
                            ->orWhere('gr_number', $input)
                            ->orWhere('phone_number', $input);
                  });
            })->first();

        Auth::login($student);
        $verification->delete();
        session()->forget('student_login_identifier');

        return redirect()->route('complaints.create');
    }
}