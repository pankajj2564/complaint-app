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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 no-print">
            
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
                                <th class="py-3 px-6 text-right">Assigned</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                            @forelse($complaints ?? [] as $complaint)
                                <tr>
                                    <!-- Clickable Ticket Number to Open Modal -->
                                    <td class="py-4 px-6 font-mono font-medium text-indigo-600">
                                        <button type="button" 
                                            @click="$dispatch('open-complaint-modal', {{ Js::from($complaint) }})"
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
                                        {{ optional($complaint->assignedEmployee)->name ?? 'Unassigned' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-400 text-sm">
                                        No complaints found.
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

        <x-complaint-modal />

    </div>
</x-app-layout>