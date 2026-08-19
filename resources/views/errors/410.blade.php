<x-frontend-layout>
    <div class="py-12 min-h-[75vh] flex items-center justify-center px-4">
        <div class="max-w-lg w-full bg-white rounded-2xl shadow-md border border-blue-100 overflow-hidden text-center p-8 sm:p-10 relative">
            
            <!-- University Accent Top Border (Navy & Gold vibe) -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-900 via-blue-700 to-amber-500"></div>

            <!-- Error Code Badge -->
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 text-blue-900 font-extrabold text-3xl mb-6 shadow-inner border border-blue-100">
                410
            </div>

            <!-- Heading -->
            <h1 class="text-2xl sm:text-3xl font-extrabold text-blue-900 mb-3 tracking-tight">
                Page Permanently Gone
            </h1>

            <!-- Description -->
            <p class="text-gray-600 text-sm sm:text-base mb-8 leading-relaxed max-w-md mx-auto">
                The complaint ticket or resource you are looking for has been permanently removed or is no longer available in the CGC University portal.
            </p>

            <!-- Action Button -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ url('/') }}" class="w-full sm:w-auto px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white font-semibold rounded-xl text-sm transition-all duration-200 shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Portal Dashboard
                </a>
            </div>

            <!-- Footer Note inside Card -->
            <div class="mt-8 pt-6 border-t border-gray-100 text-xs text-gray-400">
                CGC University Mohali &bull; Secure Complaint & Support System
            </div>

        </div>
    </div>
</x-frontend-layout>