<x-app-layout>
    <!-- Alpine.js state for Modal -->
    <div class="py-12 bg-gray-50 min-h-screen" x-data="{ openModal: false, activeComplaint: {} }">
        <div class="max-w-12xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Banner -->
            <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Manage Complaints</h1>
                    <p class="text-sm text-gray-500">Manage and view all Complaints system efficiently.</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full border border-indigo-100">
                        Admin Portal
                    </span>                    
                </div>
            </div>

            <!-- Stats Overview Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Complaints</p>
                        <h3 class="text-2xl font-bold text-indigo-600 mt-1">
                            {{ isset($complaints) ? $complaints->count() : 0 }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 font-bold">🎓</div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">In Progress</p>
                        <h3 class="text-2xl font-bold text-emerald-600 mt-1">
                            {{ isset($complaints) ? $complaints->where('status', 'in_progress')->count() : 0 }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 font-bold">✅</div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Pending</p>
                        <h3 class="text-2xl font-bold text-emerald-600 mt-1">
                            {{ isset($complaints) ? $complaints->where('status', 'pending')->count() : 0 }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 font-bold">✅</div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">New Requests</p>
                        <h3 class="text-2xl font-bold text-amber-600 mt-1">
                            {{ isset($complaints) ? $complaints->where('created_at', '>=', now()->subDays(7))->count() : 0 }}
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

            <!-- Complaints Directory Table Section -->
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Complaints Directory</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/70 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                <th class="py-3 px-6">Ticket No</th>
                                <th class="py-3 px-6">Submitted By</th>
                                <th class="py-3 px-6">Category</th>
                                <th class="py-3 px-6">Location</th>
                                <th class="py-3 px-6">Status</th>
                                <th class="py-3 px-6">Assigned To</th>
                                <th class="py-3 px-6">Date</th>
                                <th class="py-3 px-6 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @forelse($complaints ?? [] as $complaint)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <!-- Clickable Ticket Number to Open Modal -->
                                    <td class="py-4 px-6 font-mono font-medium text-indigo-600">
                                        <button type="button" 
                                            @click="activeComplaint = {{ json_encode($complaint) }}; openModal = true"
                                            class="hover:underline text-indigo-600 focus:outline-none">
                                            #{{ $complaint->ticket_number }}
                                        </button>
                                    </td>
                                    <td class="py-4 px-6 font-medium text-gray-900">
                                        {{ optional($complaint->user)->name ?? 'N/A' }}
                                        <span class="block text-xs text-gray-400">Registered: {{ $complaint->created_at->format('d M Y') }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-gray-500">{{ optional($complaint->category)->name ?? 'N/A' }}</td>
                                    <td class="py-4 px-6 text-gray-500">{{ $complaint->location ?? 'N/A' }}</td>
                                    <td class="py-4 px-6 text-gray-500">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $complaint->status == 'pending' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ ucfirst($complaint->status) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-gray-500">{{ optional($complaint->assignedEmployee)->name ?? 'Unassigned' }}</td>
                                    <td class="py-4 px-6 text-gray-500">{{ $complaint->created_at->format('d M Y, h:i A') }}</td>
                                    <td class="py-4 px-6 text-right space-x-2">
                                        <div class="inline-flex items-center gap-2">
                                            <form action="{{ route('admin.complaints.assign', $complaint->id) }}" method="POST" class="inline-flex items-center gap-1">
                                                @csrf
                                                <select name="assigned_to" required class="text-xs border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm">
                                                    <option value="">-- Select Employee --</option>
                                                    @foreach($employees ?? [] as $employee)
                                                        <option value="{{ $employee->id }}" {{ $complaint->assigned_to == $employee->id ? 'selected' : '' }}>
                                                            {{ $employee->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                
                                                <button type="submit" class="px-3 py-1 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg text-xs font-semibold transition">
                                                    Assign
                                                </button>
                                            </form>
                                            <!-- Alternatively, view button can also trigger modal -->
                                            <button type="button" @click="activeComplaint = {{ json_encode($complaint) }}; openModal = true" class="px-3 py-1 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-xs font-semibold transition">View</button>
                                            <form action="{{ route('admin.complaints_delete', $complaint->id) }}" method="POST" class="inline">
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
                                        No complaint records found in the database.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Tailwind Pagination Links --}}
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
                        Ticket: <span class="font-mono text-indigo-600" x-text="'#' + activeComplaint.ticket_number"></span>
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