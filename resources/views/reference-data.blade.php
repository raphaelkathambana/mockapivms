<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reference Data Visualizer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4" x-data="referenceDataVisualizer()">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Reference Data Visualizer</h1>
            <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800">← Back to Home</a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4">Reference Data Tables</h2>
            <p class="text-gray-600 mb-4">
                These tables contain reference data used throughout the vehicle sales management system.
                Select a table to view and manage its data.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button @click="selectTable('customer-types', 'Customer Types')"
                    class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200 hover:border-blue-500 text-left">
                    <h3 class="text-lg font-medium text-blue-600">Customer Types</h3>
                    <p class="text-gray-600 mt-1 text-sm">Types of customers (e.g., Private, Business)</p>
                </button>

                <button @click="selectTable('owner-types', 'Owner Types')"
                    class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200 hover:border-blue-500 text-left">
                    <h3 class="text-lg font-medium text-blue-600">Owner Types</h3>
                    <p class="text-gray-600 mt-1 text-sm">Types of vehicle owners</p>
                </button>

                <button @click="selectTable('fuel-types', 'Fuel Types')"
                    class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200 hover:border-blue-500 text-left">
                    <h3 class="text-lg font-medium text-blue-600">Fuel Types</h3>
                    <p class="text-gray-600 mt-1 text-sm">Types of fuel (e.g., Gasoline, Diesel, Electric)</p>
                </button>

                <button @click="selectTable('manufacturers', 'Manufacturers')"
                    class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200 hover:border-blue-500 text-left">
                    <h3 class="text-lg font-medium text-blue-600">Manufacturers</h3>
                    <p class="text-gray-600 mt-1 text-sm">Vehicle manufacturers</p>
                </button>

                <button @click="selectTable('vehicle-models', 'Vehicle Models')"
                    class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200 hover:border-blue-500 text-left">
                    <h3 class="text-lg font-medium text-blue-600">Vehicle Models</h3>
                    <p class="text-gray-600 mt-1 text-sm">Models of vehicles by manufacturer</p>
                </button>

                <button @click="selectTable('transmissions', 'Transmissions')"
                    class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200 hover:border-blue-500 text-left">
                    <h3 class="text-lg font-medium text-blue-600">Transmissions</h3>
                    <p class="text-gray-600 mt-1 text-sm">Types of transmissions (e.g., Manual, Automatic)</p>
                </button>

                <button @click="selectTable('vehicle-types', 'Vehicle Types')"
                    class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200 hover:border-blue-500 text-left">
                    <h3 class="text-lg font-medium text-blue-600">Vehicle Types</h3>
                    <p class="text-gray-600 mt-1 text-sm">Types of vehicles (e.g., Sedan, SUV, Truck)</p>
                </button>

                <button @click="selectTable('drive-types', 'Drive Types')"
                    class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200 hover:border-blue-500 text-left">
                    <h3 class="text-lg font-medium text-blue-600">Drive Types</h3>
                    <p class="text-gray-600 mt-1 text-sm">Types of drive systems (e.g., FWD, RWD, AWD)</p>
                </button>

                <button @click="selectTable('addresses', 'Addresses')"
                    class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200 hover:border-blue-500 text-left">
                    <h3 class="text-lg font-medium text-blue-600">Addresses</h3>
                    <p class="text-gray-600 mt-1 text-sm">Address records for buyers and sellers</p>
                </button>
            </div>
        </div>

        <!-- Selected Table Data -->
        <div x-show="selectedTableName" x-cloak class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold" x-text="selectedTableTitle"></h2>
                <button @click="showAddForm = true"
                    class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Add New</span>
                </button>
            </div>

            <div x-show="loading" class="text-center py-4">
                <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <p class="mt-2 text-gray-600">Loading data...</p>
            </div>

            <div x-show="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong class="font-bold">Error!</strong>
                <span x-text="error"></span>
            </div>

            <!-- Add/Edit Form -->
            <div x-show="showAddForm || showEditForm" x-cloak class="mb-6 border rounded-lg p-4 bg-gray-50">
                <h3 class="font-medium mb-3" x-text="showEditForm ? 'Edit Record' : 'Add New Record'"></h3>

                <form @submit.prevent="showEditForm ? updateRecord() : addRecord()">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <template x-for="field in formFields" :key="field.name">
                            <div>
                                <label :for="field.name" class="block text-sm font-medium mb-1"
                                    x-text="field.label"></label>

                                <!-- Select field for foreign keys -->
                                <template x-if="field.type === 'select'">
                                    <select :id="field.name" x-model="formData[field.name]"
                                        class="w-full border rounded px-3 py-2" :required="field.required">
                                        <option value="">Select...</option>
                                        <template x-for="option in field.options" :key="option.value">
                                            <option :value="option.value" x-text="option.label"></option>
                                        </template>
                                    </select>
                                </template>

                                <!-- Text input for strings -->
                                <template x-if="field.type === 'text'">
                                    <input type="text" :id="field.name" x-model="formData[field.name]"
                                        class="w-full border rounded px-3 py-2" :required="field.required">
                                </template>

                                <!-- Number input for numeric values -->
                                <template x-if="field.type === 'number'">
                                    <input type="number" :id="field.name" x-model="formData[field.name]"
                                        class="w-full border rounded px-3 py-2" :required="field.required"
                                        :step="field.step || 1">
                                </template>
                            </div>
                        </template>
                    </div>

                    <div class="mt-4 flex gap-2">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded">
                            <span x-text="showEditForm ? 'Update' : 'Add'"></span>
                        </button>
                        <button type="button" @click="cancelForm"
                            class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

            <!-- Data Table -->
            <div x-show="!loading && tableData.length > 0" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <template x-for="column in tableColumns" :key="column.key">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    x-text="column.label"></th>
                            </template>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="(record, index) in tableData" :key="index">
                            <tr>
                                <template x-for="column in tableColumns" :key="column.key">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm"
                                        x-text="getDisplayValue(record, column)"></td>
                                </template>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button @click="editRecord(record)"
                                        class="text-indigo-600 hover:text-indigo-900 mr-2">
                                        Edit
                                    </button>
                                    <button @click="confirmDelete(record)" class="text-red-600 hover:text-red-900">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div x-show="!loading && tableData.length === 0" class="text-gray-500 italic p-4 text-center">
                No records found in this table. Click "Add New" to create one.
            </div>

            <!-- Delete Confirmation Modal -->
            <div x-show="showDeleteConfirm" x-cloak
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-lg max-w-md w-full p-6">
                    <h3 class="text-lg font-medium mb-4">Confirm Deletion</h3>
                    <p class="text-gray-600 mb-4">Are you sure you want to delete this record? This action cannot be
                        undone.</p>
                    <p class="text-red-600 mb-4 text-sm">Note: Records that are referenced by other tables cannot be
                        deleted.</p>
                    <div class="flex justify-end gap-2">
                        <button @click="deleteRecord()"
                            class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded">
                            Delete
                        </button>
                        <button @click="showDeleteConfirm = false"
                            class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function referenceDataVisualizer() {
            return {
                baseUrl: window.location.origin,
                selectedTableName: '',
                selectedTableTitle: '',
                tableData: [],
                tableColumns: [],
                formFields: [],
                formData: {},
                recordToDelete: null,
                loading: false,
                error: null,
                showAddForm: false,
                showEditForm: false,
                showDeleteConfirm: false,

                selectTable(tableName, tableTitle) {
                    this.selectedTableName = tableName;
                    this.selectedTableTitle = tableTitle;
                    this.showAddForm = false;
                    this.showEditForm = false;
                    this.fetchTableData();
                    this.setupTableStructure(tableName);
                },

                async fetchTableData() {
                    if (!this.selectedTableName) return;

                    this.loading = true;
                    this.error = null;

                    try {
                        const response = await fetch(`${this.baseUrl}/${this.selectedTableName}`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`Failed to fetch data: ${response.status}`);
                        }

                        this.tableData = await response.json();
                    } catch (err) {
                        console.error('Error fetching table data:', err);
                        this.error = `Failed to load data: ${err.message}`;
                    } finally {
                        this.loading = false;
                    }
                },

                setupTableStructure(tableName) {
                    // Define table columns and form fields based on the selected table
                    switch (tableName) {
                        case 'customer-types':
                            this.tableColumns = [{
                                    key: 'customer_type_id',
                                    label: 'ID'
                                },
                                {
                                    key: 'type_name',
                                    label: 'Type Name'
                                }
                            ];
                            this.formFields = [{
                                name: 'type_name',
                                label: 'Type Name',
                                type: 'text',
                                required: true
                            }];
                            break;

                        case 'owner-types':
                            this.tableColumns = [{
                                    key: 'owner_type_id',
                                    label: 'ID'
                                },
                                {
                                    key: 'type_name',
                                    label: 'Type Name'
                                }
                            ];
                            this.formFields = [{
                                name: 'type_name',
                                label: 'Type Name',
                                type: 'text',
                                required: true
                            }];
                            break;

                        case 'fuel-types':
                            this.tableColumns = [{
                                    key: 'fuel_type_id',
                                    label: 'ID'
                                },
                                {
                                    key: 'fuel_name',
                                    label: 'Fuel Name'
                                }
                            ];
                            this.formFields = [{
                                name: 'fuel_name',
                                label: 'Fuel Name',
                                type: 'text',
                                required: true
                            }];
                            break;

                        case 'manufacturers':
                            this.tableColumns = [{
                                    key: 'manufacturer_id',
                                    label: 'ID'
                                },
                                {
                                    key: 'name',
                                    label: 'Manufacturer Name'
                                }
                            ];
                            this.formFields = [{
                                name: 'name',
                                label: 'Manufacturer Name',
                                type: 'text',
                                required: true
                            }];
                            break;

                        case 'vehicle-models':
                            this.tableColumns = [{
                                    key: 'model_id',
                                    label: 'ID'
                                },
                                {
                                    key: 'manufacturer.name',
                                    label: 'Manufacturer'
                                },
                                {
                                    key: 'model_name',
                                    label: 'Model Name'
                                }
                            ];
                            this.formFields = [{
                                    name: 'manufacturer_id',
                                    label: 'Manufacturer',
                                    type: 'select',
                                    required: true,
                                    options: [] // Will be populated when form is shown
                                },
                                {
                                    name: 'model_name',
                                    label: 'Model Name',
                                    type: 'text',
                                    required: true
                                }
                            ];
                            break;

                        case 'transmissions':
                            this.tableColumns = [{
                                    key: 'transmission_id',
                                    label: 'ID'
                                },
                                {
                                    key: 'type',
                                    label: 'Transmission Type'
                                }
                            ];
                            this.formFields = [{
                                name: 'type',
                                label: 'Transmission Type',
                                type: 'text',
                                required: true
                            }];
                            break;

                        case 'vehicle-types':
                            this.tableColumns = [{
                                    key: 'type_id',
                                    label: 'ID'
                                },
                                {
                                    key: 'type_name',
                                    label: 'Type Name'
                                }
                            ];
                            this.formFields = [{
                                name: 'type_name',
                                label: 'Type Name',
                                type: 'text',
                                required: true
                            }];
                            break;

                        case 'drive-types':
                            this.tableColumns = [{
                                    key: 'drive_type_id',
                                    label: 'ID'
                                },
                                {
                                    key: 'drive_type_name',
                                    label: 'Drive Type Name'
                                }
                            ];
                            this.formFields = [{
                                name: 'drive_type_name',
                                label: 'Drive Type Name',
                                type: 'text',
                                required: true
                            }];
                            break;

                        case 'addresses':
                            this.tableColumns = [{
                                    key: 'address_id',
                                    label: 'ID'
                                },
                                {
                                    key: 'street',
                                    label: 'Street'
                                },
                                {
                                    key: 'house_number',
                                    label: 'House Number'
                                },
                                {
                                    key: 'city',
                                    label: 'City'
                                },
                                {
                                    key: 'postal_code',
                                    label: 'Postal Code'
                                },
                                {
                                    key: 'country',
                                    label: 'Country'
                                }
                            ];
                            this.formFields = [{
                                    name: 'street',
                                    label: 'Street',
                                    type: 'text',
                                    required: true
                                },
                                {
                                    name: 'house_number',
                                    label: 'House Number',
                                    type: 'text',
                                    required: true
                                },
                                {
                                    name: 'city',
                                    label: 'City',
                                    type: 'text',
                                    required: true
                                },
                                {
                                    name: 'postal_code',
                                    label: 'Postal Code',
                                    type: 'text',
                                    required: true
                                },
                                {
                                    name: 'country',
                                    label: 'Country',
                                    type: 'text',
                                    required: true
                                }
                            ];
                            break;
                    }
                },

                getDisplayValue(record, column) {
                    // Handle nested properties like 'manufacturer.name'
                    if (column.key.includes('.')) {
                        const parts = column.key.split('.');
                        let value = record;
                        for (const part of parts) {
                            value = value?.[part];
                            if (value === undefined) return 'N/A';
                        }
                        return value;
                    }

                    return record[column.key];
                },

                async addRecord() {
                    this.loading = true;
                    this.error = null;

                    try {
                        const response = await fetch(`${this.baseUrl}/${this.selectedTableName}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify(this.formData)
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.error || errorData.message ||
                                `Failed to add record: ${response.status}`);
                        }

                        // Refresh the table data
                        await this.fetchTableData();
                        this.showAddForm = false;
                        this.formData = {};
                    } catch (err) {
                        console.error('Error adding record:', err);
                        this.error = `Failed to add record: ${err.message}`;
                    } finally {
                        this.loading = false;
                    }
                },

                editRecord(record) {
                    this.showEditForm = true;
                    this.formData = {
                        ...record
                    };

                    // For vehicle-models, we need to fetch manufacturers
                    if (this.selectedTableName === 'vehicle-models') {
                        this.fetchManufacturers();
                    }
                },

                async updateRecord() {
                    this.loading = true;
                    this.error = null;

                    // Determine the ID field name based on the table
                    const idField = this.getIdFieldName();
                    const recordId = this.formData[idField];

                    try {
                        const response = await fetch(`${this.baseUrl}/${this.selectedTableName}/${recordId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify(this.formData)
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.error || errorData.message ||
                                `Failed to update record: ${response.status}`);
                        }

                        // Refresh the table data
                        await this.fetchTableData();
                        this.showEditForm = false;
                        this.formData = {};
                    } catch (err) {
                        console.error('Error updating record:', err);
                        this.error = `Failed to update record: ${err.message}`;
                    } finally {
                        this.loading = false;
                    }
                },

                confirmDelete(record) {
                    this.recordToDelete = record;
                    this.showDeleteConfirm = true;
                },

                async deleteRecord() {
                    this.loading = true;
                    this.error = null;

                    // Determine the ID field name based on the table
                    const idField = this.getIdFieldName();
                    const recordId = this.recordToDelete[idField];

                    try {
                        const response = await fetch(`${this.baseUrl}/${this.selectedTableName}/${recordId}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.error || errorData.message ||
                                `Failed to delete record: ${response.status}`);
                        }

                        // Refresh the table data
                        await this.fetchTableData();
                        this.showDeleteConfirm = false;
                        this.recordToDelete = null;
                    } catch (err) {
                        console.error('Error deleting record:', err);
                        this.error = `Failed to delete record: ${err.message}`;
                        this.showDeleteConfirm = false;
                    } finally {
                        this.loading = false;
                    }
                },

                cancelForm() {
                    this.showAddForm = false;
                    this.showEditForm = false;
                    this.formData = {};
                },

                getIdFieldName() {
                    // Return the appropriate ID field name based on the selected table
                    switch (this.selectedTableName) {
                        case 'customer-types':
                            return 'customer_type_id';
                        case 'owner-types':
                            return 'owner_type_id';
                        case 'fuel-types':
                            return 'fuel_type_id';
                        case 'manufacturers':
                            return 'manufacturer_id';
                        case 'vehicle-models':
                            return 'model_id';
                        case 'transmissions':
                            return 'transmission_id';
                        case 'vehicle-types':
                            return 'type_id';
                        case 'drive-types':
                            return 'drive_type_id';
                        case 'addresses':
                            return 'address_id';
                        default:
                            return 'id';
                    }
                },

                async fetchManufacturers() {
                    try {
                        const response = await fetch(`${this.baseUrl}/manufacturers`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`Failed to fetch manufacturers: ${response.status}`);
                        }

                        const manufacturers = await response.json();

                        // Update the options for the manufacturer_id field
                        this.formFields.find(field => field.name === 'manufacturer_id').options =
                            manufacturers.map(m => ({
                                value: m.manufacturer_id,
                                label: m.name
                            }));
                    } catch (err) {
                        console.error('Error fetching manufacturers:', err);
                        this.error = `Failed to load manufacturers: ${err.message}`;
                    }
                }
            };
        }
    </script>
</body>

</html>
