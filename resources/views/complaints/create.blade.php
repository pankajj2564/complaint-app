<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg p-6 sm:p-8">
            
            <div class="border-b border-gray-200 pb-4 mb-6 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">University Complaint Portal</h2>
                <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full">Student Ticket Form</span>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 text-red-700 text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Basic Details Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Complainant</label>
                        <select id="complainant" name="complainant" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="toggleHostelFields()">
                            <option value="">-- Select Complainant First --</option>
                            <option value="Day Scholar Student">Day Scholar Student</option>
                            <option value="Hostel Student">Hostel Student</option>
                            <option value="Staff/employee">Staff/employee</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" value="{{ $user->name }}" readonly 
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-600 shadow-sm">
                    </div>

                    <!-- Email ID -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email ID</label>
                        <input type="email" value="{{ $user->email }}" readonly 
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-600 shadow-sm">
                    </div>

                    <!-- Mobile No (Auto Captured) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mobile No (Auto Captured)</label>
                        <input type="text" value="{{ optional($user->studentProfile)->phone_number ?? optional($user->employeeProfile)->phone_number ?? 'N/A' }}" readonly 
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-600 shadow-sm">
                    </div>                    
                    @if($user->role == 'student')
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Student Roll No</label>
                        <input type="text" value="{{ optional($user->studentProfile)->roll_number ?? 'N/A' }}" readonly 
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-600 shadow-sm">
                    </div>
                    <!-- Student Grn / Roll No -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Student Gr No</label>
                        <input type="text" value="{{ optional($user->studentProfile)->gr_number ?? 'N/A' }}" readonly 
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-600 shadow-sm">
                    </div>
                    @endif
                    @if($user->role == 'employee')
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Employee Code</label>
                        <input type="text" value="{{ optional($user->employeeProfile)->employee_code ?? 'N/A' }}" readonly 
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-600 shadow-sm">
                    </div>
                   <div>
                        <label class="block text-sm font-medium text-gray-700">Designation</label>
                        <input type="text" value="{{ optional($user->employeeProfile)->designation ?? 'N/A' }}" readonly 
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-600 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Department</label>
                        <input type="text" value="{{ optional($user->employeeProfile)->department ?? 'N/A' }}" readonly 
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-600 shadow-sm">
                    </div>
                    @endif
                    <!-- Date of Complaint (Auto) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date of Complaint</label>
                        <input type="text" value="{{ date('d-M-Y H:i') }}" readonly 
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-600 shadow-sm">
                    </div>

                    <!-- Conditional Hosteller Details (If applicable based on profile) -->
                    
                    <div id="hostel_name_field" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700">Hostel Name</label>
                        <input type="text" name="hostel_name" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-600 shadow-sm">
                    </div>
                    <div id="room_number_field" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700">Room No</label>
                        <input type="text" name="room_number" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-600 shadow-sm">
                    </div>
                    

                </div>

                <hr class="my-6 border-gray-200">

                <!-- Matrix Category Selection -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    
                    <!-- Category Dropdown -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category Selection <span class="text-red-500">*</span></label>
                        <select id="category-select" name="category_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Choose Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sub-Category Dropdown (Dynamic) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sub-Category</label>
                        <select id="sub-category-select" name="sub_category_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Select Category First --</option>
                        </select>
                    </div>

                </div>

                <!-- Location specification -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Location Specification <span class="text-red-500">*</span></label>
                    <input type="text" name="location" placeholder="For maintenance, kindly specify exact location (e.g., Block A, Near Lab 3)..." required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <!-- Detail of Issue -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Detail of Issue <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="4" placeholder="Describe your issue clearly..." required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Upload Image / Photo (Optional)</label>
                    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                    <p class="mt-1 text-xs text-gray-500">Formats allowed: JPG, PNG, JPEG. Maximum size: 2MB.</p>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-md shadow transition duration-150 ease-in-out">
                        Submit Complaint Ticket
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- AJAX Script to load Sub-Categories dynamically -->
    <script>
        document.getElementById('category-select').addEventListener('change', function () {
            let categoryId = this.value;
            let subCategorySelect = document.getElementById('sub-category-select');
            
            subCategorySelect.innerHTML = '<option value="">Loading options...</option>';

            if (!categoryId) {
                subCategorySelect.innerHTML = '<option value="">-- Select Category First --</option>';
                return;
            }

            fetch(`/api/sub-categories/${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    subCategorySelect.innerHTML = '<option value="">-- Select Sub-Category --</option>';
                    if (data.length === 0) {
                        subCategorySelect.innerHTML = '<option value="">No sub-categories available</option>';
                    } else {
                        data.forEach(sub => {
                            let option = document.createElement('option');
                            option.value = sub.id;
                            option.textContent = sub.name;
                            subCategorySelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching subcategories:', error);
                    subCategorySelect.innerHTML = '<option value="">Error loading sub-categories</option>';
                });
        });
        function toggleHostelFields() {
            const complainantSelect = document.getElementById('complainant');
            const hostelNameField = document.getElementById('hostel_name_field');
            const roomNumberField = document.getElementById('room_number_field');

            // Check agar selected value "Hostel Student" hai
            if (complainantSelect.value === 'Hostel Student') {
                hostelNameField.style.display = 'block';
                roomNumberField.style.display = 'block';
            } else {
                hostelNameField.style.display = 'none';
                roomNumberField.style.display = 'none';
            }
        }
    </script>
</x-app-layout>