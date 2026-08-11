<!-- resources/views/admin/dashboard.blade.php -->
<x-app-layout>    
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Centering Grid Container -->
            <div class="flex justify-center">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-3xl px-4">
                    
                    <!-- Card 1: Import Users -->
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500 text-center flex flex-col justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Manage Users</h3>
                            <p class="text-gray-600 text-sm mb-6">Upload CSV files to register students and employees into the system.</p>
                        </div>
                        <div>
                            <a href="{{ route('admin.import') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm font-medium">Go to CSV Import</a>
                        </div>
                    </div>

                    <!-- Card 2: View Complaints -->
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-green-500 text-center flex flex-col justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">View Complaints</h3>
                            <p class="text-gray-600 text-sm mb-6">Monitor and track all student and employee complaints across categories.</p>
                        </div>
                        <div>
                            <span class="inline-block bg-gray-200 text-gray-600 px-4 py-2 rounded text-sm font-medium cursor-not-allowed">Coming Soon</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>