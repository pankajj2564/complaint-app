<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-12xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Banner -->
            <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Employee Management</h1>
                    <p class="text-sm text-gray-500">Manage and view all registered system employees efficiently.</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full border border-indigo-100">
                        Admin Portal
                    </span>
                    <?php /* <a href="{{ route('admin.students.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        + Add Employee
                    </a> */ ?>
                </div>
            </div>

            <!-- Stats Overview Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Employees</p>
                        <h3 class="text-2xl font-bold text-indigo-600 mt-1">
                            {{ isset($employees) ? $employees->count() : 0 }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 font-bold">🎓</div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Active</p>
                        <h3 class="text-2xl font-bold text-emerald-600 mt-1">
                            {{ isset($employees) ? $employees->where('status', 'active')->count() : (isset($employees) ? $employees->count() : 0) }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 font-bold">✅</div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">New Registrations</p>
                        <h3 class="text-2xl font-bold text-amber-600 mt-1">
                            {{ isset($employees) ? $employees->where('created_at', '>=', now()->subDays(7))->count() : 0 }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 font-bold">⏳</div>
                </div>
            </div>

            <!-- Success Message Notification -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button type="button" class="text-emerald-500 hover:text-emerald-700 font-bold" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif

            <!-- Students Table Section -->
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Students Directory</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/70 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                <th class="py-3 px-6">ID / Reg No</th>
                                <th class="py-3 px-6">Name</th>
                                <th class="py-3 px-6">Email</th>
                                <th class="py-3 px-6">Phone</th>
                                <th class="py-3 px-6">Employee Code</th>
                                <th class="py-3 px-6">Status</th>
                                <th class="py-3 px-6">Date</th>
                                <th class="py-3 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @forelse($employees ?? [] as $employee)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="py-4 px-6 font-mono font-medium text-indigo-600">#{{ $employee->id }}</td>
                                    <td class="py-4 px-6 font-medium text-gray-900">
                                        {{ $employee->name }}
                                        <span class="block text-xs text-gray-400">Registered: {{ $employee->created_at->format('d M Y') }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-gray-500">{{ $employee->email }}</td>
                                    <td class="py-4 px-6 text-gray-500">{{ $employee->employeeProfile->phone_number ?? 'N/A' }}</td>
                                    <td class="py-4 px-6 text-gray-500">{{ $employee->employeeProfile->employee_code }}</td>
                                    <td class="py-4 px-6 text-gray-500">{{ $employee->status }}</td>
                                    <td class="py-4 px-6 text-gray-500">{{ $employee->created_at->format('d M Y') }} </td>                                   
                                    <td class="py-4 px-6 text-right space-x-2">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('admin.users.edit', $employee->id) }}" class="px-3 py-1 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg text-xs font-semibold transition">Edit</a>
                                            <form action="{{ route('admin.user_delete', $employee->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg text-xs font-semibold transition" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-gray-400 text-sm">
                                        No employees records found in the database.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>