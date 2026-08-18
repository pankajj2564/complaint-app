<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Students & Employees') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-950">

                    <!-- Success / Error Messages -->
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Import Form -->
                    <form action="{{ route('admin.import.process') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Role Selection -->
                        <div>
                            <label for="role" class="block font-medium text-sm text-gray-700">Select User Role to Import</label>
                            <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <option value="">-- Choose Role --</option>
                                <option value="student">Student</option>
                                <option value="employee">Employee</option>
                            </select>
                        </div>

                        <!-- CSV File Upload -->
                        <div>
                            <label for="file" class="block font-medium text-sm text-gray-700">Upload CSV File</label>
                            <input type="file" name="file" id="file" accept=".csv, .txt" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Import Data
                            </button>
                        </div>
                    </form>

                    <!-- Instructions / CSV Format Guide -->
                    <div class="mt-10 border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900">CSV File Format Instructions</h3>
                        <p class="mt-1 text-sm text-gray-600">Make sure your CSV file includes a header row matching the following column structures:</p>
                        
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Student Format Box -->
                            <div class="bg-gray-50 p-4 rounded-lg border">
                                <h4 class="font-semibold text-indigo-600">For Students:</h4>
                                <code class="text-xs text-gray-800 block mt-2 bg-white p-2 border rounded">
                                    Sr. No., Name, Email, Phone Number, Roll Number, GR Number, Course, School, Student Type
                                </code>
                            </div>

                            <!-- Employee Format Box -->
                            <div class="bg-gray-50 p-4 rounded-lg border">
                                <h4 class="font-semibold text-indigo-600">For Employees:</h4>
                                <code class="text-xs text-gray-800 block mt-2 bg-white p-2 border rounded">
                                    Sr. No., Name, Email, Phone Number, Employee Code, Designation, Department
                                </code>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>