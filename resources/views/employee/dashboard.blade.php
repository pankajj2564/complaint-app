<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Banner -->
            <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Employee Workspace</h1>
                    <p class="text-sm text-gray-500">Manage, track, and resolve department complaints efficiently.</p>
                </div>
                <span class="px-4 py-2 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full border border-indigo-100">
                    Staff Portal
                </span>
            </div>

            <!-- Stats Overview Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Pending Tickets</p>
                        <h3 class="text-2xl font-bold text-amber-600 mt-1">
                            {{ isset($complaints) ? $complaints->where('status', 'pending')->count() : 0 }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 font-bold">⏳</div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">In Progress</p>
                        <h3 class="text-2xl font-bold text-indigo-600 mt-1">
                            {{ isset($complaints) ? $complaints->where('status', 'in_progress')->count() : 0 }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 font-bold">🔄</div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Resolved Tickets</p>
                        <h3 class="text-2xl font-bold text-emerald-600 mt-1">
                            {{ isset($complaints) ? $complaints->where('status', 'resolved')->count() : 0 }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 font-bold">✅</div>
                </div>
            </div>

            <!-- Assigned Complaints Table Section -->
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Assigned Complaints</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/70 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                <th class="py-3 px-6">Ticket No</th>
                                <th class="py-3 px-6">Complainant</th>
                                <th class="py-3 px-6">Category</th>
                                <th class="py-3 px-6">Status</th>
                                <th class="py-3 px-6 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @forelse($complaints ?? [] as $complaint)
                                <tr>
                                    <td class="py-4 px-6 font-mono font-medium text-indigo-600">{{ $complaint->ticket_number }}</td>
                                    <td class="py-4 px-6 font-medium text-gray-900">
                                        {{ optional($complaint->user)->name ?? 'Unknown' }} 
                                        <span class="block text-xs text-gray-400">{{ optional($complaint->user)->role }}</span>
                                    </td>
                                    <td class="py-4 px-6">{{ optional($complaint->category)->name ?? 'N/A' }}</td>
                                    <td class="py-4 px-6">
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full 
                                            @if($complaint->status === 'pending') bg-amber-50 text-amber-700 
                                            @elseif($complaint->status === 'in_progress') bg-indigo-50 text-indigo-700 
                                            @else bg-emerald-50 text-emerald-700 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <!-- Form to quick-update status -->
                                        <form action="{{ route('employee.complaints.update', $complaint->id) }}" method="POST" class="inline-flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()" class="text-xs border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="in_progress" {{ $complaint->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                                <option value="closed" {{ $complaint->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-400 text-sm">
                                        No complaints assigned to you yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Links Section -->
                <div class="mt-4 px-6 py-4">
                    {{ $complaints->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>