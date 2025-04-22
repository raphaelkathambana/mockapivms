{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Seller Model Visualizer</title>
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
    <div class="container mx-auto p-4" x-data="sellerVisualizer()">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Seller Model Visualizer</h1>
            <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800">← Back to Home</a>
        </div>

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Left side - API Buttons -->
            <div class="w-full md:w-1/3 bg-white p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-4">Seller Actions</h2>

                <div class="space-y-3">
                    <div>
                        <h3 class="font-medium mb-2">Sellers</h3>
                        <button @click="fetchSellers()"
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded mb-2 flex justify-between items-center">
                            <span>GET /sellers</span>
                            <span class="text-xs bg-green-800 px-2 py-1 rounded">GET</span>
                        </button>

                        <button @click="showCreateSellerForm = true; formMode = 'create'; fetchRelatedData()"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded mb-2 flex justify-between items-center">
                            <span>Create New Seller</span>
                            <span class="text-xs bg-blue-800 px-2 py-1 rounded">POST</span>
                        </button>
                    </div>

                    <div x-show="selectedSeller" x-cloak>
                        <h3 class="font-medium mb-2">Selected Seller: <span
                                x-text="selectedSeller?.first_name + ' ' + selectedSeller?.last_name"></span></h3>
                        <div class="space-y-2">
                            <button @click="showSeller(selectedSeller.seller_id)"
                                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded flex justify-between items-center">
                                <span>GET /sellers/<span x-text="selectedSeller.seller_id"></span></span>
                                <span class="text-xs bg-green-800 px-2 py-1 rounded">GET</span>
                            </button>

                            <button @click="showEditSellerForm(selectedSeller)"
                                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-2 px-4 rounded flex justify-between items-center">
                                <span>Edit Seller</span>
                                <span class="text-xs bg-yellow-800 px-2 py-1 rounded">PUT</span>
                            </button>

                            <button @click="confirmDeleteSeller(selectedSeller.seller_id)"
                                class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded flex justify-between items-center">
                                <span>Delete Seller</span>
                                <span class="text-xs bg-red-800 px-2 py-1 rounded">DELETE</span>
                            </button>
                        </div>
                    </div>

                    <!-- Delete Confirmation -->
                    <div x-show="showDeleteConfirm" x-cloak class="bg-red-50 border border-red-200 rounded p-3">
                        <p class="text-red-700 mb-2">Are you sure you want to delete this seller?</p>
                        <p class="text-red-700 mb-2 text-sm">This action cannot be undone. Sellers with related vehicles
                            cannot be deleted.</p>
                        <div class="flex gap-2">
                            <button @click="deleteSeller()"
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
                <!-- Seller Form -->
                <div x-show="showSellerForm" x-cloak class="bg-white p-4 rounded-lg shadow mb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold"
                            x-text="formMode === 'create' ? 'Create New Seller' : 'Edit Seller'"></h2>
                        <button @click="showSellerForm = false" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form @submit.prevent="formMode === 'create' ? createSeller() : updateSeller()">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- First Name -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium mb-1">First Name</label>
                                <input type="text" x-model="sellerForm.first_name"
                                    class="w-full border rounded px-3 py-2" required>
                            </div>

                            <!-- Last Name -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium mb-1">Last Name</label>
                                <input type="text" x-model="sellerForm.last_name"
                                    class="w-full border rounded px-3 py-2" required>
                            </div>

                            <!-- Gender -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium mb-1">Gender</label>
                                <select x-model="sellerForm.gender" class="w-full border rounded px-3 py-2" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <!-- Customer Type -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium mb-1">Customer Type</label>
                                <select x-model="sellerForm.customer_type_id" class="w-full border rounded px-3 py-2"
                                    required>
                                    <option value="">Select Customer Type</option>
                                    <template x-for="type in relatedData.customerTypes" :key="type.customer_type_id">
                                        <option :value="type.customer_type_id" x-text="type.type_name"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Address -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium mb-1">Address</label>
                                <select x-model="sellerForm.address_id" class="w-full border rounded px-3 py-2"
                                    required>
                                    <option value="">Select Address</option>
                                    <template x-for="address in relatedData.addresses" :key="address.address_id">
                                        <option :value="address.address_id"
                                            x-text="address.street + ' ' + address.house_number + ', ' + address.city">
                                        </option>
                                    </template>
                                </select>
                            </div>

                            <!-- Phone Number -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium mb-1">Phone Number</label>
                                <input type="text" x-model="sellerForm.phone_number"
                                    class="w-full border rounded px-3 py-2" required>
                            </div>

                            <!-- Email -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium mb-1">Email</label>
                                <input type="email" x-model="sellerForm.email"
                                    class="w-full border rounded px-3 py-2" required>
                            </div>
                        </div>

                        <div class="mt-4 flex gap-2">
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded">
                                <span x-text="formMode === 'create' ? 'Create Seller' : 'Update Seller'"></span>
                            </button>
                            <button type="button" @click="showSellerForm = false"
                                class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Results display area -->
                <div x-show="!showSellerForm" class="bg-white p-4 rounded-lg shadow">
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

                    <!-- Seller Detail View -->
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
                            <!-- Tabs for seller details -->
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
                                        <a href="#" @click.prevent="activeTab = 'vehicles'"
                                            :class="{ 'tab-active': activeTab === 'vehicles' }"
                                            class="inline-block p-2 font-medium">
                                            Vehicles Sold
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Basic Info Tab -->
                            <div x-show="activeTab === 'basic'" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="border rounded p-3">
                                        <h3 class="font-medium mb-2">Seller Information</h3>
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Seller ID:</span>
                                                <span class="font-medium" x-text="responseData.seller_id"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Full Name:</span>
                                                <span class="font-medium"
                                                    x-text="responseData.first_name + ' ' + responseData.last_name"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Gender:</span>
                                                <span class="font-medium" x-text="responseData.gender"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Customer Type:</span>
                                                <span class="font-medium"
                                                    x-text="responseData.customerType?.type_name"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="border rounded p-3">
                                        <h3 class="font-medium mb-2">Contact Information</h3>
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Phone:</span>
                                                <span class="font-medium" x-text="responseData.phone_number"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Email:</span>
                                                <span class="font-medium" x-text="responseData.email"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="border rounded p-3 col-span-2">
                                        <h3 class="font-medium mb-2">Address</h3>
                                        <div class="space-y-2" x-show="responseData.address">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Street:</span>
                                                <span class="font-medium"
                                                    x-text="responseData.address?.street + ' ' + responseData.address?.house_number"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">City:</span>
                                                <span class="font-medium" x-text="responseData.address?.city"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Postal Code:</span>
                                                <span class="font-medium"
                                                    x-text="responseData.address?.postal_code"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Country:</span>
                                                <span class="font-medium"
                                                    x-text="responseData.address?.country"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Vehicles Tab -->
                            <div x-show="activeTab === 'vehicles'" class="space-y-4">
                                <div x-show="responseData.vehicles && responseData.vehicles.length > 0">
                                    <h3 class="font-medium mb-2">Vehicles Sold by this Seller</h3>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        VIN</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Manufacturer</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Model</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Purchase Price</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <template x-for="vehicle in responseData.vehicles"
                                                    :key="vehicle.vin">
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm"
                                                            x-text="vehicle.vin"></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm"
                                                            x-text="vehicle.manufacturer?.name"></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm"
                                                            x-text="vehicle.model?.model_name"></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm"
                                                            x-text="'€' + vehicle.purchase_price"></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                            <span
                                                                :class="{
                                                                    'bg-green-100 text-green-800': vehicle
                                                                        .status === 'Available',
                                                                    'bg-red-100 text-red-800': vehicle
                                                                        .status === 'Sold',
                                                                    'bg-yellow-100 text-yellow-800': vehicle
                                                                        .status === 'Reserved'
                                                                }"
                                                                class="px-2 py-1 text-xs rounded-full"
                                                                x-text="vehicle.status">
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div x-show="!responseData.vehicles || responseData.vehicles.length === 0"
                                    class="text-gray-500 italic">
                                    No vehicles sold by this seller.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Seller List View -->
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
                            <h3 class="font-medium mb-2" x-text="`${responseData.length} sellers found`"></h3>
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
                                                Customer Type</th>
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
                                        <template x-for="seller in responseData" :key="seller.seller_id">
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm"
                                                    x-text="seller.seller_id"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm"
                                                    x-text="seller.first_name + ' ' + seller.last_name"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm"
                                                    x-text="seller.customerType?.type_name"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm" x-text="seller.email">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm"
                                                    x-text="seller.phone_number"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <button @click="selectSeller(seller)"
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
                        No sellers found in the database.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function sellerVisualizer() {
            return {
                baseUrl: window.location.origin,
                responseData: null,
                responseStatus: null,
                currentEndpoint: '',
                error: null,
                loading: false,
                lastRequestTime: '',
                selectedSeller: null,
                sellerToDeleteId: null,
                showSellerForm: false,
                formMode: 'create', // 'create' or 'edit'
                showDeleteConfirm: false,
                viewMode: 'list', // 'list' or 'detail'
                activeTab: 'basic',
                relatedData: {
                    addresses: [],
                    customerTypes: []
                },
                sellerForm: {
                    first_name: '',
                    last_name: '',
                    gender: '',
                    address_id: '',
                    phone_number: '',
                    email: '',
                    customer_type_id: ''
                },

                get showCreateSellerForm() {
                    return this.showSellerForm && this.formMode === 'create';
                },

                set showCreateSellerForm(value) {
                    this.showSellerForm = value;
                    if (value) {
                        this.formMode = 'create';
                        this.resetSellerForm();
                    }
                },

                resetSellerForm() {
                    this.sellerForm = {
                        first_name: '',
                        last_name: '',
                        gender: '',
                        address_id: '',
                        phone_number: '',
                        email: '',
                        customer_type_id: ''
                    };
                },

                async fetchSellers() {
                    this.viewMode = 'list';
                    await this.makeRequest('GET', '/api/sellers');
                },

                async showSeller(id) {
                    this.viewMode = 'detail';
                    this.activeTab = 'basic';
                    await this.makeRequest('GET', `/api/sellers/${id}`);
                },

                selectSeller(seller) {
                    this.selectedSeller = seller;
                },

                async fetchRelatedData() {
                    try {
                        const response = await fetch(this.baseUrl + '/api/sellers/related-data', {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`Failed to fetch related data: ${response.status}`);
                        }

                        this.relatedData = await response.json();
                    } catch (err) {
                        console.error('Error fetching related data:', err);
                        this.error = 'Failed to load form data. Please try again.';
                    }
                },

                showEditSellerForm(seller) {
                    this.formMode = 'edit';
                    this.fetchRelatedData().then(() => {
                        this.sellerForm = {
                            first_name: seller.first_name,
                            last_name: seller.last_name,
                            gender: seller.gender,
                            address_id: seller.address_id,
                            phone_number: seller.phone_number,
                            email: seller.email,
                            customer_type_id: seller.customer_type_id
                        };
                        this.showSellerForm = true;
                    });
                },

                async createSeller() {
                    await this.makeRequest('POST', '/api/sellers', this.sellerForm);
                    if (!this.error) {
                        this.showSellerForm = false;
                        this.resetSellerForm();
                        await this.fetchSellers();
                    }
                },

                async updateSeller() {
                    await this.makeRequest('PUT', `/api/sellers/${this.selectedSeller.seller_id}`, this.sellerForm);
                    if (!this.error) {
                        this.showSellerForm = false;

                        // Refresh the sellers list and the selected seller
                        await this.fetchSellers();
                        if (this.selectedSeller) {
                            await this.showSeller(this.selectedSeller.seller_id);
                        }
                    }
                },

                confirmDeleteSeller(id) {
                    this.sellerToDeleteId = id;
                    this.showDeleteConfirm = true;
                },

                async deleteSeller() {
                    await this.makeRequest('DELETE', `/api/sellers/${this.sellerToDeleteId}`);
                    this.showDeleteConfirm = false;
                    this.sellerToDeleteId = null;

                    // Refresh the sellers list and clear selection
                    if (!this.error) {
                        this.selectedSeller = null;
                        await this.fetchSellers();
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

</html> --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Seller Visualizer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .container { margin-top: 20px; }
        .card { margin-bottom: 20px; }
        #sellerForm, #sellerDetails { display: none; }
        .loading { display: none; }
        .error-message { color: red; margin-top: 10px; }
        .success-message { color: green; margin-top: 10px; }
        .tab-content { padding: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Seller Visualizer</h1>
        <div class="row">
            <div class="col-md-6 mb-3">
                <button id="showFormBtn" class="btn btn-primary">Add New Seller</button>
                <a href="/" class="btn btn-secondary">Back to Home</a>
            </div>
            <div class="col-md-6 mb-3">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search sellers...">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Seller Form -->
        <div class="card" id="sellerForm">
            <div class="card-header">
                <h5 id="formTitle">Add New Seller</h5>
            </div>
            <div class="card-body">
                <form id="sellerDataForm">
                    <input type="hidden" id="sellerId">

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="address_id" class="form-label">Address</label>
                            <select class="form-select" id="address_id" required>
                                <option value="">Select Address</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="customer_type_id" class="form-label">Customer Type</label>
                            <select class="form-select" id="customer_type_id" required>
                                <option value="">Select Customer Type</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Save Seller</button>
                        <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
                    </div>
                    <div class="loading mt-3">Saving seller data...</div>
                    <div class="error-message"></div>
                    <div class="success-message"></div>
                </form>
            </div>
        </div>

        <!-- Seller Details -->
        <div class="card" id="sellerDetails">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Seller Details</h5>
                <div>
                    <button id="editSellerBtn" class="btn btn-sm btn-primary">Edit</button>
                    <button id="backToListBtn" class="btn btn-sm btn-secondary">Back to List</button>
                </div>
            </div>
            <div class="card-body">
                <div class="loading">Loading seller details...</div>
                <div class="error-message"></div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <h4 id="detailName">Seller Details</h4>
                        <p><strong>Gender:</strong> <span id="detailGender"></span></p>
                        <p><strong>Customer Type:</strong> <span id="detailCustomerType"></span></p>
                        <p><strong>Phone Number:</strong> <span id="detailPhoneNumber"></span></p>
                        <p><strong>Email:</strong> <span id="detailEmail"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h4>Address</h4>
                        <p id="detailAddress"></p>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <h4>Vehicles Sold</h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>VIN</th>
                                        <th>Vehicle</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="vehiclesSoldTableBody">
                                    <!-- Vehicles sold will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sellers List -->
        <div class="card">
            <div class="card-header">
                <h5>Sellers List</h5>
            </div>
            <div class="card-body">
                <div class="loading">Loading sellers...</div>
                <div class="error-message"></div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Customer Type</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sellersTableBody">
                            <!-- Sellers will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const sellersTableBody = document.getElementById('sellersTableBody');
            const sellerForm = document.getElementById('sellerForm');
            const sellerDetails = document.getElementById('sellerDetails');
            const sellerDataForm = document.getElementById('sellerDataForm');
            const showFormBtn = document.getElementById('showFormBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const formTitle = document.getElementById('formTitle');
            const backToListBtn = document.getElementById('backToListBtn');
            const editSellerBtn = document.getElementById('editSellerBtn');
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');

            // Form fields
            const sellerIdField = document.getElementById('sellerId');
            const firstNameField = document.getElementById('first_name');
            const lastNameField = document.getElementById('last_name');
            const genderField = document.getElementById('gender');
            const addressIdField = document.getElementById('address_id');
            const customerTypeIdField = document.getElementById('customer_type_id');
            const phoneNumberField = document.getElementById('phone_number');
            const emailField = document.getElementById('email');

            // UI elements
            const loadingElements = document.querySelectorAll('.loading');
            const errorElements = document.querySelectorAll('.error-message');
            const successElements = document.querySelectorAll('.success-message');

            // Show/hide UI elements
            function showLoading() {
                loadingElements.forEach(el => el.style.display = 'block');
            }

            function hideLoading() {
                loadingElements.forEach(el => el.style.display = 'none');
            }

            function showError(message) {
                errorElements.forEach(el => {
                    el.textContent = message;
                    el.style.display = 'block';
                });
            }

            function hideError() {
                errorElements.forEach(el => {
                    el.textContent = '';
                    el.style.display = 'none';
                });
            }

            function showSuccess(message) {
                successElements.forEach(el => {
                    el.textContent = message;
                    el.style.display = 'block';
                });

                // Hide success message after 3 seconds
                setTimeout(() => {
                    successElements.forEach(el => {
                        el.style.display = 'none';
                    });
                }, 3000);
            }

            // Fetch all sellers
            function fetchSellers(searchTerm = '') {
                showLoading();
                hideError();

                let url = '/api/sellers';
                if (searchTerm) {
                    url += `?search=${encodeURIComponent(searchTerm)}`;
                }

                fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch sellers');
                    }
                    return response.json();
                })
                .then(data => {
                    hideLoading();
                    renderSellers(data);
                })
                .catch(error => {
                    hideLoading();
                    showError('Error loading sellers: ' + error.message);
                    console.error('Error:', error);
                });
            }

            // Render sellers in the table
            function renderSellers(sellers) {
                sellersTableBody.innerHTML = '';

                if (sellers.length === 0) {
                    const row = document.createElement('tr');
                    row.innerHTML = '<td colspan="6" class="text-center">No sellers found</td>';
                    sellersTableBody.appendChild(row);
                    return;
                }

                sellers.forEach(seller => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${seller.seller_id}</td>
                        <td>${seller.first_name} ${seller.last_name}</td>
                        <td>${seller.customer_type ? seller.customer_type.type_name : '-'}</td>
                        <td>${seller.email}</td>
                        <td>${seller.phone_number}</td>
                        <td>
                            <button class="btn btn-sm btn-info view-btn" data-id="${seller.seller_id}">View</button>
                            <button class="btn btn-sm btn-primary edit-btn" data-id="${seller.seller_id}">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${seller.seller_id}">Delete</button>
                        </td>
                    `;
                    sellersTableBody.appendChild(row);
                });

                // Add event listeners to buttons
                document.querySelectorAll('.view-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        viewSeller(id);
                    });
                });

                document.querySelectorAll('.edit-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        editSeller(id);
                    });
                });

                document.querySelectorAll('.delete-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        deleteSeller(id);
                    });
                });
            }

            // Load addresses and customer types for dropdowns
            function loadDropdownOptions() {
                // Load addresses
                fetch('/api/addresses', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    addressIdField.innerHTML = '<option value="">Select Address</option>';
                    data.forEach(address => {
                        addressIdField.innerHTML += `<option value="${address.address_id}">${address.street} ${address.house_number}, ${address.postal_code} ${address.city}, ${address.country}</option>`;
                    });
                })
                .catch(error => console.error('Error loading addresses:', error));

                // Load customer types
                fetch('/api/customer-types', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    customerTypeIdField.innerHTML = '<option value="">Select Customer Type</option>';
                    data.forEach(type => {
                        customerTypeIdField.innerHTML += `<option value="${type.customer_type_id}">${type.type_name}</option>`;
                    });
                })
                .catch(error => console.error('Error loading customer types:', error));
            }

            // Show form for adding a new seller
            showFormBtn.addEventListener('click', function() {
                formTitle.textContent = 'Add New Seller';
                sellerIdField.value = '';
                sellerDataForm.reset();
                sellerForm.style.display = 'block';
                sellerDetails.style.display = 'none';
                showFormBtn.style.display = 'none';

                // Load dropdown options
                loadDropdownOptions();
            });

            // Hide form
            cancelBtn.addEventListener('click', function() {
                sellerForm.style.display = 'none';
                showFormBtn.style.display = 'block';
                hideError();
            });

            // Back to list from details view
            backToListBtn.addEventListener('click', function() {
                sellerDetails.style.display = 'none';
                showFormBtn.style.display = 'block';
            });

            // Edit from details view
            editSellerBtn.addEventListener('click', function() {
                const id = sellerIdField.value;
                editSeller(id);
            });

            // Handle form submission
            sellerDataForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const sellerId = sellerIdField.value;
                const sellerData = {
                    first_name: firstNameField.value,
                    last_name: lastNameField.value,
                    gender: genderField.value,
                    address_id: addressIdField.value,
                    customer_type_id: customerTypeIdField.value,
                    phone_number: phoneNumberField.value,
                    email: emailField.value
                };

                if (sellerId) {
                    updateSeller(sellerId, sellerData);
                } else {
                    createSeller(sellerData);
                }
            });

            // Search functionality
            searchBtn.addEventListener('click', function() {
                const searchTerm = searchInput.value.trim();
                fetchSellers(searchTerm);
            });

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const searchTerm = searchInput.value.trim();
                    fetchSellers(searchTerm);
                }
            });

            // Create a new seller
            function createSeller(sellerData) {
                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/api/sellers', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(sellerData)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Failed to create seller');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    hideLoading();
                    sellerForm.style.display = 'none';
                    showFormBtn.style.display = 'block';
                    showSuccess('Seller created successfully!');
                    fetchSellers();
                })
                .catch(error => {
                    hideLoading();
                    showError('Error creating seller: ' + error.message);
                    console.error('Error:', error);
                });
            }

            // View seller details
            function viewSeller(id) {
                showLoading();
                hideError();

                fetch(`/api/sellers/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch seller details');
                    }
                    return response.json();
                })
                .then(seller => {
                    hideLoading();

                    // Store seller ID for edit button
                    sellerIdField.value = seller.seller_id;

                    // Display seller details
                    document.getElementById('detailName').textContent = `${seller.first_name} ${seller.last_name}`;
                    document.getElementById('detailGender').textContent = seller.gender;
                    document.getElementById('detailCustomerType').textContent = seller.customer_type ? seller.customer_type.type_name : '-';
                    document.getElementById('detailPhoneNumber').textContent = seller.phone_number;
                    document.getElementById('detailEmail').textContent = seller.email;

                    // Format address
                    const address = seller.address;
                    if (address) {
                        document.getElementById('detailAddress').innerHTML = `
                            ${address.street} ${address.house_number}<br>
                            ${address.postal_code} ${address.city}<br>
                            ${address.country}
                        `;
                    } else {
                        document.getElementById('detailAddress').textContent = 'No address information';
                    }

                    // Load vehicles sold
                    const vehiclesSoldTableBody = document.getElementById('vehiclesSoldTableBody');
                    vehiclesSoldTableBody.innerHTML = '';

                    if (seller.vehicles && seller.vehicles.length > 0) {
                        seller.vehicles.forEach(vehicle => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${vehicle.vin}</td>
                                <td>${vehicle.year} ${vehicle.manufacturer ? vehicle.manufacturer.name : ''} ${vehicle.model ? vehicle.model.name : ''}</td>
                                <td>${vehicle.purchase_price ? `$${parseFloat(vehicle.purchase_price).toFixed(2)}` : '-'}</td>
                                <td>${vehicle.status}</td>
                            `;
                            vehiclesSoldTableBody.appendChild(row);
                        });
                    } else {
                        const row = document.createElement('tr');
                        row.innerHTML = '<td colspan="5" class="text-center">No vehicles sold</td>';
                        vehiclesSoldTableBody.appendChild(row);
                    }

                    // Show the details view
                    sellerDetails.style.display = 'block';
                    sellerForm.style.display = 'none';
                    showFormBtn.style.display = 'none';
                })
                .catch(error => {
                    hideLoading();
                    showError('Error loading seller details: ' + error.message);
                    console.error('Error:', error);
                });
            }

            // Fetch seller details for editing
            function editSeller(id) {
                showLoading();
                hideError();

                fetch(`/api/sellers/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch seller details');
                    }
                    return response.json();
                })
                .then(seller => {
                    hideLoading();

                    // Load dropdown options
                    loadDropdownOptions();

                    // Set form title and seller ID
                    formTitle.textContent = `Edit Seller: ${seller.first_name} ${seller.last_name}`;
                    sellerIdField.value = seller.seller_id;

                    // Populate form fields
                    firstNameField.value = seller.first_name;
                    lastNameField.value = seller.last_name;
                    genderField.value = seller.gender;
                    phoneNumberField.value = seller.phone_number;
                    emailField.value = seller.email;

                    // Set dropdown values after a short delay to ensure they're loaded
                    setTimeout(() => {
                        if (seller.address_id) {
                            addressIdField.value = seller.address_id;
                        }
                        if (seller.customer_type_id) {
                            customerTypeIdField.value = seller.customer_type_id;
                        }
                    }, 500);

                    // Show the form
                    sellerForm.style.display = 'block';
                    sellerDetails.style.display = 'none';
                    showFormBtn.style.display = 'none';
                })
                .catch(error => {
                    hideLoading();
                    showError('Error loading seller details: ' + error.message);
                    console.error('Error:', error);
                });
            }

            // Update an existing seller
            function updateSeller(id, sellerData) {
                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/api/sellers/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(sellerData)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Failed to update seller');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    hideLoading();
                    sellerForm.style.display = 'none';
                    showFormBtn.style.display = 'block';
                    showSuccess('Seller updated successfully!');
                    fetchSellers();
                })
                .catch(error => {
                    hideLoading();
                    showError('Error updating seller: ' + error.message);
                    console.error('Error:', error);
                });
            }

            // Delete a seller
            function deleteSeller(id) {
                if (!confirm('Are you sure you want to delete this seller?')) {
                    return;
                }

                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/api/sellers/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Failed to delete seller');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    hideLoading();
                    showSuccess('Seller deleted successfully!');
                    fetchSellers();
                })
                .catch(error => {
                    hideLoading();
                    showError('Error deleting seller: ' + error.message);
                    console.error('Error:', error);
                });
            }

            // Initial load
            fetchSellers();
        });
    </script>
</body>
</html>
