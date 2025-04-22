<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employee Model Visualizer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        .tab-active {
            border-bottom: 2px solid #3b82f6;
            color: #3b82f6;
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4" x-data="employeeVisualizer()">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Employee Model Visualizer</h1>
            <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800">← Back to Home</a>
        </div>

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Left side - API Buttons -->
            <div class="w-full md:w-1/3 bg-white p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-4">Employee Actions</h2>

                <div class="space-y-3">
                    <div>
                        <h3 class="font-medium mb-2">Employees</h3>
                        <button @click="fetchEmployees()"
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded mb-2 flex justify-between items-center">
                            <span>GET /employees</span>
                            <span class="text-xs bg-green-800 px-2 py-1 rounded">GET</span>
                        </button>

                        <button @click="showCreateEmployeeForm = true; formMode = 'create'"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded mb-2 flex justify-between items-center">
                            <span>Create New Employee</span>
                            <span class="text-xs bg-blue-800 px-2 py-1 rounded">POST</span>
                        </button>
                    </div>

                    <div x-show="selectedEmployee" x-cloak>
                        <h3 class="font-medium mb-2">Selected Employee: <span
                                x-text="selectedEmployee?.first_name + ' ' + selectedEmployee?.last_name"></span></h3>
                        <div class="space-y-2">
                            <button @click="showEmployee(selectedEmployee.employee_id)"
                                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded flex justify-between items-center">
                                <span>GET /employees/<span x-text="selectedEmployee.employee_id"></span></span>
                                <span class="text-xs bg-green-800 px-2 py-1 rounded">GET</span>
                            </button>

                            <button @click="showEditEmployeeForm(selectedEmployee)"
                                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-2 px-4 rounded flex justify-between items-center">
                                <span>Edit Employee</span>
                                <span class="text-xs bg-yellow-800 px-2 py-1 rounded">PUT</span>
                            </button>

                            <button @click="confirmDeleteEmployee(selectedEmployee.employee_id)"
                                class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded flex justify-between items-center">
                                <span>Delete Employee</span>
                                <span class="text-xs bg-red-800 px-2 py-1 rounded">DELETE</span>
                            </button>
                        </div>
                    </div>

                    <!-- Delete Confirmation -->
                    <div x-show="showDeleteConfirm" x-cloak class="bg-red-50 border border-red-200 rounded p-3">
                        <p class="text-red-700 mb-2">Are you sure you want to delete this employee?</p>
                        <p class="text-red-700 mb-2 text-sm">This action cannot be undone. Employees with related
                            records cannot be deleted.</p>
                        <div class="flex gap-2">
                            <button @click="deleteEmployee()"
                                class="bg-red-600 hover:bg-red-700 text-white py-1 px-3 rounded">
                                Delete
                            </button>
                            <button @click="showDeleteConfirm = false"
                                class="bg-gray-500 hover:bg-gray-600 text-white py-1 px-3 rounded">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side - Results -->
            <div class="w-full md:w-2/3">
                <!-- Employee Form -->
                <div x-show="showEmployeeForm" x-cloak class="bg-white p-4 rounded-lg shadow mb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold"
                            x-text="formMode === 'create' ? 'Create New Employee' : 'Edit Employee'"></h2>
                        <button @click="showEmployeeForm = false" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form @submit.prevent="formMode === 'create' ? createEmployee() : updateEmployee()">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- First Name -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium mb-1">First Name</label>
                                <input type="text" x-model="employeeForm.first_name"
                                    class="w-full border rounded px-3 py-2" required>
                            </div>

                            <!-- Last Name -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium mb-1">Last Name</label>
                                <input type="text" x-model="employeeForm.last_name"
                                    class="w-full border rounded px-3 py-2" required>
                            </div>

                            <!-- Role -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium mb-1">Role</label>
                                <input type="text" x-model="employeeForm.role"
                                    class="w-full border rounded px-3 py-2" required>
                            </div>

                            <!-- Email -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium mb-1">Email</label>
                                <input type="email" x-model="employeeForm.email"
                                    class="w-full border rounded px-3 py-2" required>
                            </div>

                            <!-- Phone Number -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium mb-1">Phone Number</label>
                                <input type="text" x-model="employeeForm.phone_number"
                                    class="w-full border rounded px-3 py-2" required>
                            </div>
                        </div>

                        <div class="mt-4 flex gap-2">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded">
                                <span x-text="formMode === 'create' ? 'Create Employee' : 'Update Employee'"></span>
                            </button>
                            <button type="button" @click="showEmployeeForm = false"
                                class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Results display area -->
                <div x-show="!showEmployeeForm" class="bg-white p-4 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold">Results</h2>
                        <div class="flex items-center gap-2">
                            <span x-show="loading" class="text-sm text-gray-500">Loading...</span>
                            <span x-show="lastRequestTime" class="text-xs text-gray-500"
                                x-text="'Last request: ' + lastRequestTime">
                            </span>
                        </div>
                    </div>

                    <div x-show="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <strong class="font-bold">Error!</strong>
                        <span x-text="error"></span>
                    </div>

                    <!-- Employee Detail View -->
                    <div x-show="viewMode === 'detail' && !Array.isArray(responseData)"
                        class="border rounded-lg overflow-hidden">
                        <div class="bg-gray-100 px-4 py-2 border-b flex justify-between items-center">
                            <div class="font-medium" x-text="currentEndpoint || 'No request made'"></div>
                            <div class="text-sm" x-show="responseStatus">
                                Status: <span x-text="responseStatus"
                                    :class="{
                                        'text-green-600': responseStatus >= 200 && responseStatus < 300,
                                        'text-yellow-600': responseStatus >= 300 && responseStatus < 400,
                                        'text-red-600': responseStatus >= 400
                                    }">
                                </span>
                            </div>
                        </div>

                        <div class="p-4">
                            <!-- Tabs for employee details -->
                            <div class="border-b mb-4">
                                <ul class="flex flex-wrap -mb-px">
                                    <li class="mr-2">
                                        <a href="#" @click.prevent="activeTab = 'basic'"
                                            :class="{ 'tab-active': activeTab === 'basic' }"
                                            class="inline-block p-2 font-medium">
                                            Basic Info
                                        </a>
                                    </li>
                                    <li class="mr-2">
                                        <a href="#" @click.prevent="activeTab = 'contracts'"
                                            :class="{ 'tab-active': activeTab === 'contracts' }"
                                            class="inline-block p-2 font-medium">
                                            Contracts
                                        </a>
                                    </li>
                                    <li class="mr-2">
                                        <a href="#" @click.prevent="activeTab = 'logs'"
                                            :class="{ 'tab-active': activeTab === 'logs' }"
                                            class="inline-block p-2 font-medium">
                                            Sales Logs
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Basic Info Tab -->
                            <div x-show="activeTab === 'basic'" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="border rounded p-3">
                                        <h3 class="font-medium mb-2">Employee Information</h3>
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Employee ID:</span>
                                                <span class="font-medium" x-text="responseData.employee_id"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Full Name:</span>
                                                <span class="font-medium"
                                                    x-text="responseData.first_name + ' ' + responseData.last_name"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Role:</span>
                                                <span class="font-medium" x-text="responseData.role"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="border rounded p-3">
                                        <h3 class="font-medium mb-2">Contact Information</h3>
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Email:</span>
                                                <span class="font-medium" x-text="responseData.email"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Phone:</span>
                                                <span class="font-medium" x-text="responseData.phone_number"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contracts Tab -->
                            <div x-show="activeTab === 'contracts'" class="space-y-4">
                                <div
                                    x-show="responseData.purchaseContracts && responseData.purchaseContracts.length > 0">
                                    <h3 class="font-medium mb-2">Purchase Contracts</h3>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Contract ID</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Vehicle VIN</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Customer</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Contract Date</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <template x-for="contract in responseData.purchaseContracts"
                                                    :key="contract.contract_id">
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm"
                                                            x-text="contract.contract_id"></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm"
                                                            x-text="contract.vin"></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm"
                                                            x-text="contract.buyer ? contract.buyer.first_name + ' ' + contract.buyer.last_name : 'N/A'">
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm"
                                                            x-text="contract.contract_date"></td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div x-show="!responseData.purchaseContracts || responseData.purchaseContracts.length === 0"
                                    class="text-gray-500 italic">
                                    No purchase contracts for this employee.
                                </div>
                            </div>

                            <!-- Sales Logs Tab -->
                            <div x-show="activeTab === 'logs'" class="space-y-4">
                                <div x-show="responseData.salesLogs && responseData.salesLogs.length > 0">
                                    <h3 class="font-medium mb-2">Sales Activity Logs</h3>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Timestamp</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Vehicle VIN</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Customer</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Status Change</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <template x-for="log in responseData.salesLogs" :key="log.log_id">
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm"
                                                            x-text="log.timestamp"></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm"
                                                            x-text="log.vin"></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm"
                                                            x-text="log.customer_number"></td>
                                                        <td class="px-6 py-4 text-sm" x-text="log.status_change"></td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div x-show="!responseData.salesLogs || responseData.salesLogs.length === 0"
                                    class="text-gray-500 italic">
                                    No sales activity logs for this employee.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Employee List View -->
                    <div x-show="viewMode === 'list' && Array.isArray(responseData) && responseData.length > 0"
                        class="border rounded-lg overflow-hidden">
                        <div class="bg-gray-100 px-4 py-2 border-b flex justify-between items-center">
                            <div class="font-medium" x-text="currentEndpoint || 'No request made'"></div>
                            <div class="text-sm" x-show="responseStatus">
                                Status: <span x-text="responseStatus"
                                    :class="{
                                        'text-green-600': responseStatus >= 200 && responseStatus < 300,
                                        'text-yellow-600': responseStatus >= 300 && responseStatus < 400,
                                        'text-red-600': responseStatus >= 400
                                    }">
                                </span>
                            </div>
                        </div>

                        <div class="p-4">
                            <h3 class="font-medium mb-2" x-text="`${responseData.length} employees found`"></h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                ID</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Name</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Role</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Email</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Phone</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <template x-for="employee in responseData" :key="employee.employee_id">
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm"
                                                    x-text="employee.employee_id"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm"
                                                    x-text="employee.first_name + ' ' + employee.last_name"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm"
                                                    x-text="employee.role"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm"
                                                    x-text="employee.email"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm"
                                                    x-text="employee.phone_number"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <button @click="selectEmployee(employee)"
                                                        class="text-indigo-600 hover:text-indigo-900 mr-2">
                                                        Select
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div x-show="!responseData && !loading && !error" class="text-gray-500 italic p-4">
                        No data to display. Use the buttons on the left to make API requests.
                    </div>

                    <div x-show="Array.isArray(responseData) && responseData.length === 0"
                        class="text-gray-500 italic p-4">
                        No employees found in the database.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function employeeVisualizer() {
            return {
                baseUrl: window.location.origin,
                responseData: null,
                responseStatus: null,
                currentEndpoint: '',
                error: null,
                loading: false,
                lastRequestTime: '',
                selectedEmployee: null,
                employeeToDeleteId: null,
                showEmployeeForm: false,
                formMode: 'create', // 'create' or 'edit'
                showDeleteConfirm: false,
                viewMode: 'list', // 'list' or 'detail'
                activeTab: 'basic',
                employeeForm: {
                    first_name: '',
                    last_name: '',
                    role: '',
                    email: '',
                    phone_number: ''
                },

                get showCreateEmployeeForm() {
                    return this.showEmployeeForm && this.formMode === 'create';
                },

                set showCreateEmployeeForm(value) {
                    this.showEmployeeForm = value;
                    if (value) {
                        this.formMode = 'create';
                        this.resetEmployeeForm();
                    }
                },

                resetEmployeeForm() {
                    this.employeeForm = {
                        first_name: '',
                        last_name: '',
                        role: '',
                        email: '',
                        phone_number: ''
                    };
                },

                async fetchEmployees() {
                    this.viewMode = 'list';
                    await this.makeRequest('GET', '/employees');
                },

                async showEmployee(id) {
                    this.viewMode = 'detail';
                    this.activeTab = 'basic';
                    await this.makeRequest('GET', `/employees/${id}`);
                },

                selectEmployee(employee) {
                    this.selectedEmployee = employee;
                },

                showEditEmployeeForm(employee) {
                    this.formMode = 'edit';
                    this.employeeForm = {
                        first_name: employee.first_name,
                        last_name: employee.last_name,
                        role: employee.role,
                        email: employee.email,
                        phone_number: employee.phone_number
                    };
                    this.showEmployeeForm = true;
                },

                async createEmployee() {
                    await this.makeRequest('POST', '/employees', this.employeeForm);
                    if (!this.error) {
                        this.showEmployeeForm = false;
                        this.resetEmployeeForm();
                        await this.fetchEmployees();
                    }
                },

                async updateEmployee() {
                    await this.makeRequest('PUT', `/employees/${this.selectedEmployee.employee_id}`, this.employeeForm);
                    if (!this.error) {
                        this.showEmployeeForm = false;

                        // Refresh the employees list and the selected employee
                        await this.fetchEmployees();
                        if (this.selectedEmployee) {
                            await this.showEmployee(this.selectedEmployee.employee_id);
                        }
                    }
                },

                confirmDeleteEmployee(id) {
                    this.employeeToDeleteId = id;
                    this.showDeleteConfirm = true;
                },

                async deleteEmployee() {
                    await this.makeRequest('DELETE', `/employees/${this.employeeToDeleteId}`);
                    this.showDeleteConfirm = false;
                    this.employeeToDeleteId = null;

                    // Refresh the employees list and clear selection
                    if (!this.error) {
                        this.selectedEmployee = null;
                        await this.fetchEmployees();
                    }
                },

                async makeRequest(method, endpoint, data = null) {
                    this.loading = true;
                    this.error = null;
                    this.currentEndpoint = `${method} ${endpoint}`;

                    try {
                        const options = {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            credentials: 'include'
                        };

                        if (data) {
                            options.body = JSON.stringify(data);
                        }

                        const response = await fetch(this.baseUrl + endpoint, options);
                        this.responseStatus = response.status;

                        // Update timestamp
                        const now = new Date();
                        this.lastRequestTime = now.toLocaleTimeString();

                        if (!response.ok) {
                            const errorData = await response.json().catch(() => null);
                            throw new Error(errorData?.message || errorData?.error ||
                                `Request failed with status ${response.status}`);
                        }

                        this.responseData = await response.json();
                    } catch (err) {
                        this.error = err.message;
                        console.error('API request error:', err);
                    } finally {
                        this.loading = false;
                    }
                }
            };
        }
    </script>
</body>

</html>
