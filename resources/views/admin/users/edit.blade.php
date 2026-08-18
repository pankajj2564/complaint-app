<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                
                @if ($errors->any())
                    <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-600 px-4 py-3 rounded-lg text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Common User Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" id="role" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="employee" {{ $user->role === 'employee' ? 'selected' : '' }}>Employee</option>
                                <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Student</option>
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <input type="text" name="status" id="status" value="{{ old('status', $user->status) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                        </div>
                    </div>

                    <hr class="my-6 border-gray-200">

                    <!-- Dynamic Profile Fields based on Role -->
                    @if ($user->role === 'student' && $user->studentProfile)
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Student Profile Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $user->studentProfile->phone_number) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Roll Number</label>
                                <input type="text" name="roll_number" value="{{ old('roll_number', $user->studentProfile->roll_number) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">GR Number</label>
                                <input type="text" name="gr_number" value="{{ old('gr_number', $user->studentProfile->gr_number) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Student Type</label>
                                <input type="text" name="student_type" value="{{ old('student_type', $user->studentProfile->student_type) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Course</label>
                                <input type="text" name="course" value="{{ old('course', $user->studentProfile->course) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">School</label>
                                <input type="text" name="school" value="{{ old('school', $user->studentProfile->school) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                            </div>
                        </div>
                    @endif

                    @if ($user->role === 'employee' && $user->employeeProfile)
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Employee Profile Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $user->employeeProfile->phone_number) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Employee Code</label>
                                <input type="text" name="employee_code" value="{{ old('employee_code', $user->employeeProfile->employee_code) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Department</label>
                                <input type="text" name="department" value="{{ old('department', $user->employeeProfile->department) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Designation</label>
                                <input type="text" name="designation" value="{{ old('designation', $user->employeeProfile->designation) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                            </div>
                        </div>
                    @endif

                    <!-- Submit & Cancel Buttons -->
                    <div class="flex items-center justify-end space-x-3 mt-6">
                        <a href="{{ $user->role === 'employee' ? route('admin.employees') : route('admin.students') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition">
                            Update User
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>