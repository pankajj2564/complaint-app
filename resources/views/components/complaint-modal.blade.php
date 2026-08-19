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
<div x-data="{ openModal: false, activeComplaint: {} }"
     @open-complaint-modal.window="activeComplaint = $event.detail; openModal = true"
     id="print-modal" 
     x-show="openModal" 
     style="display: none;"
     class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    
    <div @click.away="openModal = false" 
         class="bg-white rounded-2xl shadow-xl max-w-2xl w-full p-6 relative">
        
        <!-- Modal Header -->
        <div class="flex justify-between items-center pb-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">
                Ticket: <span class="font-mono text-indigo-600" x-text="activeComplaint.ticket_number"></span>
            </h3>
            <button onclick="window.print()" class="no-print text-indigo-600 hover:text-indigo-800 font-semibold text-sm flex items-center">
                🖨️ Print
            </button>
        </div>

        <!-- Modal Body -->
        <div class="py-4 space-y-3 text-lg text-gray-600">
            <div>
                <span class="font-semibold text-gray-700">Complainant Type: </span> 
                <span x-text="activeComplaint.complainant"></span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Complainant Name: </span> 
                <span x-text="activeComplaint.user ? activeComplaint.user.name : 'N/A'"></span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Category: </span> 
                <span x-text="activeComplaint.category ? activeComplaint.category.name : 'N/A'"></span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Sub Category: </span> 
                <span x-text="activeComplaint.subcategory ? activeComplaint.subcategory.name : 'N/A'"></span>
            </div>

            <!-- Hostel Details -->
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
            <button @click="openModal = false" class="no-print px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl text-sm transition-colors">
                Close
            </button>
        </div>
    </div>
</div>