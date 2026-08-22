<x-app-layout>
    <style>
        @media print {
            /* 1. Reset body and html to clear height/margin constraints */
            html, body {
                height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
            }

            /* 2. Hide everything on the page by default */
            body * {
                visibility: hidden;
            }

            /* 3. Show only the modal and its contents */
            #print-modal, 
            #print-modal * {
                visibility: visible;
            }

            /* 4. Force modal to absolute top left and clear extra height */
            #print-modal {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
                overflow: visible !important;
            }

            /* 5. Clean up modal card styling for single page fit */
            #print-modal > div {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                box-shadow: none !important;
                border: none !important;
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 10px !important;
            }

            /* 6. Ensure buttons and unwanted elements are fully removed */
            .no-print {
                display: none !important;
            }
        }
    </style>
    <!-- Alpine.js state for Modal -->
    <div class="py-12 bg-gray-50 min-h-screen" x-data="{ openModal: false, activeComplaint: {} }">
        <div class="max-w-12xl mx-auto sm:px-6 lg:px-8 no-print">
            
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
                                <th class="py-3 px-6">Image</th>
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
                                            @click="$dispatch('open-complaint-modal', {{ Js::from($complaint) }})"
                                            class="hover:underline text-indigo-600 focus:outline-none">
                                            {{ $complaint->ticket_number }}
                                        </button>
                                    </td>
                                    <td class="py-4 px-6 font-medium text-gray-900">
                                        {{ optional($complaint->user)->name ?? 'N/A' }}
                                        <span class="block text-xs text-gray-400">Registered: {{ $complaint->created_at->format('d M Y') }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-gray-500">{{ optional($complaint->category)->name ?? 'N/A' }}</td>
                                    <td class="py-4 px-6 text-gray-500">{{ $complaint->location ?? 'N/A' }}</td>
                                    <td class="py-4 px-6 text-gray-500">
                                        @if($complaint->image)
                                        <img src="{{ asset($complaint->image) }}" alt="Complaint Image" class="w-16 h-auto rounded aspect-square">
                                        @endif
                                    </td>
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

        <x-complaint-modal />

    </div>
</x-app-layout>