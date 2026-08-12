<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Verify OTP</h2>
        
        @if(session('debug_otp'))
            <div class="mb-4 text-xs text-blue-600 bg-blue-50 p-2 rounded">
                [Dev Debug Only] Your OTP code is: <strong>{{ session('debug_otp') }}</strong>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600 bg-red-50 p-3 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('student.verify.otp') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Enter 6-digit OTP code sent to Email & Phone</label>
                <input type="text" name="otp" required autofocus maxlength="6"
                    class="mt-1 block w-full rounded-md border-gray-300 tracking-widest text-center text-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 font-semibold">
                Verify & Open Complaint Form
            </button>
        </form>
    </div>
</x-guest-layout>