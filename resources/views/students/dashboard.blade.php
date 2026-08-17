<x-app-layout>
    <div class="space-y-6 pt-5">
        
        <!-- Admin Metrics Cards -->        
        <div class="grid grid-cols-1 sm:grid-cols-5 gap-5">
            <div class="bg-white p-5 rounded-2xl shadow-xs border border-gray-100">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Complaints</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $data["total"] ?? 0 }}</h3>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-xs border border-gray-100">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Pending</p>
                <h3 class="text-2xl font-bold text-amber-600 mt-1">{{ $data["pending"] ?? 0 }}</h3>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-xs border border-gray-100">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">In Progress</p>
                <h3 class="text-2xl font-bold text-indigo-600 mt-1">{{ $data["inProgress"] ?? 0 }}</h3>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-xs border border-gray-100">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Resolved</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-1">{{ $data["resolved"] ?? 0 }}</h3>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-xs border border-gray-100">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Closed</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-1">{{ $data["closed"] ?? 0 }}</h3>
            </div>
        </div>

        <!-- Recent Complaints with Status Table -->
        <div class="bg-white shadow-xs rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 font-bold text-gray-800">
                All System Complaints & Status
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                            <th class="py-3 px-6">Ticket No</th>
                            <th class="py-3 px-6">User / Submitted By</th>
                            <th class="py-3 px-6">Category</th>
                            <th class="py-3 px-6">Assigned To</th>
                            <th class="py-3 px-6">Location</th>
                            <th class="py-3 px-6">Status</th>
                            <th class="py-3 px-6">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-600">
                        
                        @forelse($data["complaints"] as $complaint)
                            <tr>
                                <!-- Ticket Number -->
                                <td class="py-4 px-6 font-mono font-medium text-indigo-600"><strong>{{ $complaint->ticket_number }}</strong></td>

                                <!-- User Details (Student/Employee) -->
                                <td class="py-4 px-6 font-medium text-gray-900">
                                    {{ $complaint->user->name ?? 'N/A' }} <br>
                                    <small class="text-muted">{{ $complaint->user->email ?? '' }}</small>
                                </td>

                                <!-- Category Name -->
                                <td class="py-4 px-6">{{ $complaint->category->name ?? 'N/A' }}</td>

                                <!-- Assigned Employee -->
                                <td class="py-4 px-6">
                                    @if($complaint->assignedEmployee)
                                        {{ $complaint->assignedEmployee->name }}
                                    @else
                                        <span class="badge bg-warning text-dark">Unassigned</span>
                                    @endif
                                </td>

                                <!-- Location -->
                                <td class="py-4 px-6">{{ $complaint->location }}</td>

                                <!-- Status Badge -->
                                <td class="py-4 px-6">
                                    @if($complaint->status == 'pending')
                                        <span class="badge bg-danger">Pending</span>
                                    @elseif($complaint->status == 'in_progress')
                                        <span class="badge bg-info">In Progress</span>
                                    @else
                                        <span class="badge bg-success">Resolved</span>
                                    @endif
                                </td>
                                <!-- Date -->
                                <td class="py-4 px-6">{{ $complaint->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No complaints found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>