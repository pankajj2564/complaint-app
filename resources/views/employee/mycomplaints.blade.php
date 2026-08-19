<x-app-layout>
    <!-- Alpine.js state for Modal -->
    <div class="py-12 bg-gray-50 min-h-screen" x-data="{ openModal: false, activeComplaint: {} }">
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
                    <h3 class="font-bold text-gray-800">My Complaints</h3>
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
                                    <!-- Clickable Ticket Number to Open Modal -->
                                    <td class="py-4 px-6 font-mono font-medium text-indigo-600">
                                        <button type="button" 
                                            @click="activeComplaint = {{ json_encode($complaint) }}; openModal = true"
                                            class="hover:underline text-indigo-600 focus:outline-none">
                                            {{ $complaint->ticket_number }}
                                        </button>
                                    </td>
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

        <!-- ================= COMPLAINT DETAILS MODAL ================= -->
        <div x-show="openModal" 
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
            
            <div @click.away="openModal = false" 
                 class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6 relative">
                
                <!-- Modal Header -->
                <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">
                        Ticket: <span class="font-mono text-indigo-600" x-text="activeComplaint.ticket_number"></span>
                    </h3>
                    <button @click="openModal = false" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
                </div>

                <!-- Modal Body -->
                <div class="py-4 space-y-3 text-sm text-gray-600">
                    <div>
                        <span class="font-semibold text-gray-700">Complainant Type: </span> 
                        <span x-text="activeComplaint.complainant"></span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-700">Category: </span> 
                        <span x-text="activeComplaint.category ? activeComplaint.category.name : 'N/A'"></span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-700">Sub Category: </span> 
                        <span x-text="activeComplaint.subcategory ? activeComplaint.subcategory.name : 'N/A'"></span>
                    </div>

                    <!-- Hostel Details (Shows only if Complainant is Hostel Student) -->
                    <template x-if="activeComplaint.complainant === 'Hostel Student'">
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 space-y-1">
                            <div><span class="font-semibold text-gray-700">Hostel Name:</span> <span x-text="activeComplaint.hostel_name"></span></div>
                            <div><span class="font-semibold text-gray-700">Room No:</span> <span x-text="activeComplaint.room_number"></span></div>
                        </div>
                    </template>
                    <div>
                        <span class="font-semibold text-gray-700">Location Specification: </span> 
                        <span x-text="activeComplaint.location ? activeComplaint.location : 'N/A'"></span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-700 block mb-1">Description:</span>
                        <p class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-gray-800" x-text="activeComplaint.description || 'No description provided.'"></p>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-700">Current Status: </span> 
                        <span class="uppercase font-bold text-xs px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700" x-text="activeComplaint.status"></span>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button @click="openModal = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl text-sm transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
        <!-- ================= END MODAL ================= -->

    </div>
</x-app-layout>