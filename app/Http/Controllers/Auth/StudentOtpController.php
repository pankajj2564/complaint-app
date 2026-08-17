<?php 

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\VerificationCode;
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

        // User dhoondhein
        $user = User::where('email', $input)
            ->orWhereHas('studentProfile', function($q) use ($input) {
                $q->where('roll_number', $input)->orWhere('gr_number', $input);
            })
            ->orWhereHas('employeeProfile', function($q) use ($input) {
                $q->where('employee_code', $input);
            })
            ->first();

        if (!$user) {
            return back()->withErrors(['login_identifier' => 'No account found with this Email, Roll No, or Employee Code.']);
        }

        if (!in_array($user->role, ['student', 'employee'])) {
            return back()->withErrors(['login_identifier' => 'Unauthorized account type for OTP login.']);
        }

        if ($user->status === 'suspended') {
            return back()->withErrors(['login_identifier' => 'Your account has been suspended. Please contact Admin.']);
        }

        $otp = rand(100000, 999999);

        // FIX: Identifier mein hamesha user ki actual email save karein taaki confusion na ho!
        VerificationCode::updateOrCreate(
            ['identifier' => $user->email],
            [
                'otp' => Hash::make($otp),
                'expires_at' => Carbon::now()->addMinutes(10)
            ]
        );

        try {
            Mail::to($user->email)->send(new SendOtpMail($otp));
        } catch (\Exception $e) {
            \Log::error('OTP Email Error: ' . $e->getMessage());
        }

        // Session mein bhi email save karein
        session(['auth_login_email' => $user->email]);
        session()->flash('debug_otp', $otp);

        return redirect()->route('student.verify.otp.form');
    }

    public function showVerifyForm()
    {
        if (!session()->has('auth_login_email')) {
            return redirect()->route('student.login');
        }
        return view('auth.student-verify');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);
        
        $email = session('auth_login_email');
        $verification = VerificationCode::where('identifier', $email)->first();

        if (!$verification || Carbon::now()->greaterThan($verification->expires_at)) {
            return back()->withErrors(['otp' => 'The OTP has expired or is invalid.']);
        }

        if (!Hash::check($request->otp, $verification->otp)) {
            return back()->withErrors(['otp' => 'Incorrect OTP code entered.']);
        }

        // Seedha email se user nikal lein kyuki email unique hoti hai
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('student.login')->withErrors(['login_identifier' => 'User session expired. Please login again.']);
        }

        Auth::login($user);
        $verification->delete();
        session()->forget('auth_login_email');

        // Role-based redirection
        if ($user->role === 'employee') {
            return redirect()->route('employee.dashboard');
        }
        
        return redirect()->route('complaints.create');
    }
}