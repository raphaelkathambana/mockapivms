<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VMS Jobs Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100" x-data="{ selectedJob: null }">
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">VMS Jobs Dashboard</h1>
            <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800">← Back to Home</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Panel - Job Buttons -->
            <div class="space-y-4">
                <button @click="selectedJob = 'procurement'"
                    class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200 flex items-center justify-between">
                    <span>Vehicle Procurement</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <button @click="selectedJob = 'sale'"
                    class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-700 transition-colors duration-200 flex items-center justify-between">
                    <span>Vehicle Sale</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Right Panel - Job Details -->
            <div class="md:col-span-2 bg-white rounded-lg shadow-md p-6" x-data="procurementData">
                <!-- No Selection State -->
                <div x-show="!selectedJob" class="h-full flex items-center justify-center">
                    <div class="text-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 mb-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <h3 class="text-lg font-medium mb-2">Select a Job</h3>
                        <p>Choose a job from the left panel to view its details and start the process</p>
                    </div>
                </div>

                <!-- Procurement Job -->
                <div x-show="selectedJob === 'procurement'" x-cloak>
                    <h2 class="text-xl font-semibold mb-4">Vehicle Procurement Process</h2>

                    <!-- Initial Options -->
                    <div x-show.important="showOptions" class="space-y-4">
                        <p class="text-gray-600">Choose how you would like to proceed:</p>
                        <div class="space-y-3">
                            <button @click="startNewVehicle()"
                                class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                Start with New Car
                            </button>
                            <button @click="startConfirmation()"
                                class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-700 transition-colors">
                                Confirmation Process for Existing Car
                            </button>
                        </div>
                    </div>

                    <!-- New Vehicle Form -->
                    <div x-show="newVehicle" class="space-y-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium">New Vehicle Details</h3>
                            <button @click="newVehicle = false; showOptions = true"
                                class="text-gray-600 hover:text-gray-800">← Back</button>
                        </div>

                        <!-- Manufacturer Modal -->
                        <div x-show="showManufacturerModal"
                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                            <div class="bg-white p-6 rounded-lg w-96">
                                <h4 class="text-lg font-medium mb-4">Add New Manufacturer</h4>
                                <form @submit.prevent="addNewManufacturer">
                                    <div class="space-y-4">
                                        <div>
                                            <label for="manufacturer_name"
                                                class="block text-sm font-medium text-gray-700">Manufacturer
                                                Name</label>
                                            <input type="text" id="manufacturer_name" x-model="newManufacturer.name"
                                                required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        </div>
                                        <div>
                                            <label for="manufacturer_country_of_origin"
                                                class="block text-sm font-medium text-gray-700">Country of
                                                Origin</label>
                                            <input type="text" id="manufacturer_country_of_origin"
                                                x-model="newManufacturer.country_of_origin" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        </div>
                                        <div class="flex justify-end space-x-2">
                                            <button type="button" @click="showManufacturerModal = false"
                                                class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</button>
                                            <button type="submit"
                                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Model Modal -->
                        <div x-show="showModelModal"
                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                            <div class="bg-white p-6 rounded-lg w-96">
                                <h4 class="text-lg font-medium mb-4">Add New Model</h4>
                                <form @submit.prevent="addNewModel">
                                    <div class="space-y-4">
                                        <div>
                                            <label for="model_name"
                                                class="block text-sm font-medium text-gray-700">Model Name</label>
                                            <input type="text" id="model_name" x-model="newModel.name" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        </div>
                                        <div>
                                            <label for="model_year"
                                                class="block text-sm font-medium text-gray-700">Year</label>
                                            <input type="number" id="model_year" x-model="newModel.year" required
                                                min="1900" x-init="$el.max = new Date().toLocaleDateString('en-US', { year: 'numeric' }) * 1 + 1"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        </div>
                                        <div class="flex justify-end space-x-2">
                                            <button type="button" @click="showModelModal = false"
                                                class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</button>
                                            <button type="submit"
                                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <form id="newVehicleForm" class="space-y-4" @submit.prevent="submitNewVehicle">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="manufacturer"
                                        class="block text-sm font-medium text-gray-700">Manufacturer</label>
                                    <div class="mt-1 flex space-x-2">
                                        <select id="manufacturer" name="manufacturer" required
                                            @change="handleManufacturerChange($event)"
                                            class="block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="">Select Manufacturer</option>
                                        </select>
                                        <button type="button" @click="showManufacturerModal = true"
                                            class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                            +
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label for="model"
                                        class="block text-sm font-medium text-gray-700">Model</label>
                                    <div class="mt-1 flex space-x-2">
                                        <select id="model" name="model" required
                                            :disabled="!selectedManufacturer"
                                            class="block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="">Select Model</option>
                                        </select>
                                        <button type="button" @click="showModelModal = true"
                                            :disabled="!selectedManufacturer"
                                            class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:bg-gray-400">
                                            +
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label for="vehicle_type" class="block text-sm font-medium text-gray-700">Vehicle
                                        Type</label>
                                    <select id="vehicle_type" name="vehicle_type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Select Type</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="mileage"
                                        class="block text-sm font-medium text-gray-700">Mileage</label>
                                    <input type="number" id="mileage" name="mileage" required min="0"
                                        step="5000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="fuel_type" class="block text-sm font-medium text-gray-700">Fuel
                                        Type</label>
                                    <select id="fuel_type" name="fuel_type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Select Fuel Type</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="transmission"
                                        class="block text-sm font-medium text-gray-700">Transmission</label>
                                    <select id="transmission" name="transmission" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Select Transmission</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="drive_type" class="block text-sm font-medium text-gray-700">Drive
                                        Type</label>
                                    <select id="drive_type" name="drive_type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Select Drive Type</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="color"
                                        class="block text-sm font-medium text-gray-700">Color</label>
                                    <input type="text" id="color" name="color" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="price"
                                        class="block text-sm font-medium text-gray-700">Price</label>
                                    <input type="number" id="price" name="price" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="seller_phone" class="block text-sm font-medium text-gray-700">Seller
                                        Phone</label>
                                    <input type="tel" id="seller_phone" name="seller_phone" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="engine_power_hp"
                                        class="block text-sm font-medium text-gray-700">Engine Power (hp)</label>
                                    <input type="number" id="engine_power_hp" name="engine_power_hp" required
                                        min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                        @input="updateKW($event)">
                                </div>
                                <div>
                                    <label for="engine_power_kw"
                                        class="block text-sm font-medium text-gray-700">Engine Power (kW)</label>
                                    <input type="number" id="engine_power_kw" name="engine_power_kw" required
                                        min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                        @input="updateHP($event)" readonly>
                                </div>
                                <div>
                                    <label for="engine_displacement"
                                        class="block text-sm font-medium text-gray-700">Engine Displacement
                                        (cc)</label>
                                    <input type="number" id="engine_displacement" name="engine_displacement"
                                        required min="0" step="1"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Save and Continue to Confirmation
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Seller Modal -->
                    <div x-show="showSellerModal"
                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white p-6 rounded-lg w-96">
                            <h4 class="text-lg font-medium mb-4">Add New Seller</h4>
                            <form @submit.prevent="addNewSeller">
                                <div class="space-y-4">
                                    <div>
                                        <label for="seller_first_name"
                                            class="block text-sm font-medium text-gray-700">First Name</label>
                                        <input type="text" id="seller_first_name" x-model="newSeller.first_name"
                                            required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label for="seller_last_name"
                                            class="block text-sm font-medium text-gray-700">Last Name</label>
                                        <input type="text" id="seller_last_name" x-model="newSeller.last_name"
                                            required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label for="seller_gender"
                                            class="block text-sm font-medium text-gray-700">Gender</label>
                                        <select id="seller_gender" x-model="newSeller.gender" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="seller_phone"
                                            class="block text-sm font-medium text-gray-700">Phone Number</label>
                                        <input type="tel" id="seller_phone" x-model="newSeller.phone_number"
                                            required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label for="seller_email"
                                            class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" id="seller_email" x-model="newSeller.email" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label for="seller_address"
                                            class="block text-sm font-medium text-gray-700">Address</label>
                                        <div class="mt-1 flex space-x-2">
                                            <select id="seller_address" x-model="newSeller.address_id" required
                                                class="block w-full rounded-md border-gray-300 shadow-sm">
                                                <option value="">Select Address</option>
                                            </select>
                                            <button type="button" @click="showAddressModal = true"
                                                class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="seller_type"
                                            class="block text-sm font-medium text-gray-700">Customer Type</label>
                                        <select id="seller_type" x-model="newSeller.customer_type_id" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="">Select Type</option>
                                        </select>
                                    </div>
                                    <div class="flex justify-end space-x-2">
                                        <button type="button" @click="showSellerModal = false"
                                            class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Address Modal -->
                    <div x-show="showAddressModal"
                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white p-6 rounded-lg w-96">
                            <h4 class="text-lg font-medium mb-4">Add New Address</h4>
                            <form @submit.prevent="addNewAddress">
                                <div class="space-y-4">
                                    <div>
                                        <label for="address_street"
                                            class="block text-sm font-medium text-gray-700">Street</label>
                                        <input type="text" id="address_street" x-model="newAddress.street"
                                            required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label for="address_house_number"
                                            class="block text-sm font-medium text-gray-700">House Number</label>
                                        <input type="text" id="address_house_number"
                                            x-model="newAddress.house_number" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label for="address_city"
                                            class="block text-sm font-medium text-gray-700">City</label>
                                        <input type="text" id="address_city" x-model="newAddress.city" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label for="address_postal_code"
                                            class="block text-sm font-medium text-gray-700">Postal Code</label>
                                        <input type="text" id="address_postal_code"
                                            x-model="newAddress.postal_code" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label for="address_country"
                                            class="block text-sm font-medium text-gray-700">Country</label>
                                        <input type="text" id="address_country" x-model="newAddress.country"
                                            required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div class="flex justify-end space-x-2">
                                        <button type="button" @click="showAddressModal = false"
                                            class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Confirmation Process -->
                    <div x-show="confirmation" class="space-y-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium">Confirmation Process</h3>
                            <button @click="confirmation = false; showOptions = true"
                                class="text-gray-600 hover:text-gray-800">← Back</button>
                        </div>

                        <!-- Vehicle Selection for Confirmation -->
                        <div x-show="!selectedVehicle" class="space-y-4">
                            <div class="mb-4">
                                <label for="vehicleSelect" class="block text-sm font-medium text-gray-700">Select
                                    Vehicle</label>
                                <select id="vehicleSelect"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                    @change="handleVehicleSelect($event)">
                                    <option value="">Choose a vehicle</option>
                                </select>
                            </div>
                        </div>

                        <!-- Confirmation Form -->
                        <div x-show="selectedVehicle" class="space-y-4">
                            <form id="confirmationForm" class="space-y-4" @submit.prevent="submitConfirmation">
                                <input type="hidden" name="original_vin" x-model="originalVin">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="vin_input"
                                            class="block text-sm font-medium text-gray-700">VIN</label>
                                        <input type="text" id="vin_input" name="vin"
                                            x-model="confirmationVin" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label for="previous_owners"
                                            class="block text-sm font-medium text-gray-700">Previous Owners</label>
                                        <input type="number" id="previous_owners" name="previous_owners" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label for="sellerSelect"
                                            class="block text-sm font-medium text-gray-700">Seller</label>
                                        <div class="mt-1 flex space-x-2">
                                            <select id="sellerSelect" name="seller_id" required
                                                class="block w-full rounded-md border-gray-300 shadow-sm">
                                                <option value="">Select Seller</option>
                                            </select>
                                            <button type="button"
                                                @click="console.log('Opening seller modal...'); showSellerModal = true"
                                                class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="warranty_status"
                                            class="block text-sm font-medium text-gray-700">Warranty Status</label>
                                        <select id="warranty_status" name="warranty_status" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="active">Active</option>
                                            <option value="expired">Expired</option>
                                            <option value="none">No Warranty</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="inspection_status"
                                            class="block text-sm font-medium text-gray-700">Inspection Status</label>
                                        <select id="inspection_status" name="inspection_status" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value="passed">Passed</option>
                                            <option value="failed">Failed</option>
                                            <option value="pending">Pending</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Damage Records -->
                                <fieldset class="space-y-2">
                                    <legend class="block text-sm font-medium text-gray-700">Damage Records</legend>
                                    <div id="damageRecords" class="space-y-2"></div>
                                    <button type="button" class="text-blue-600 hover:text-blue-800 text-sm"
                                        onclick="addDamageRecord()">+ Add Damage Record</button>
                                </fieldset>

                                <!-- Additional Equipment -->
                                <fieldset class="space-y-2">
                                    <legend class="block text-sm font-medium text-gray-700">Additional Equipment
                                    </legend>
                                    <div id="additionalEquipment" class="space-y-2"></div>
                                    <button type="button" class="text-blue-600 hover:text-blue-800 text-sm"
                                        onclick="addEquipment()">+ Add Equipment</button>
                                </fieldset>

                                <!-- Tires Information -->
                                <fieldset class="space-y-2">
                                    <legend class="block text-sm font-medium text-gray-700">Tires</legend>
                                    <div class="grid grid-cols-2 gap-4">
                                        <!-- Front Left Tire -->
                                        <div class="border p-3 rounded">
                                            <h4 class="font-medium mb-2">Front Left Tire</h4>
                                            <input type="hidden" name="tire_position_fl" value="left-front">
                                            <div class="space-y-2">
                                                <div>
                                                    <label for="tire_tread_depth_fl"
                                                        class="block text-xs text-gray-500">Tread Depth (mm)</label>
                                                    <input type="number" id="tire_tread_depth_fl" step="0.1"
                                                        name="tire_tread_depth_fl" min="0" max="20"
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                                </div>
                                                <div>
                                                    <label for="tire_rim_type_fl"
                                                        class="block text-xs text-gray-500">Rim Type</label>
                                                    <select id="tire_rim_type_fl" name="tire_rim_type_fl"
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                                        <option value="Steel">Steel</option>
                                                        <option value="Aluminum">Aluminum</option>
                                                        <option value="Alloy">Alloy</option>
                                                        <option value="Magnesium">Magnesium</option>
                                                        <option value="Carbon Fiber">Carbon Fiber</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label for="tire_type_fl" class="block text-xs text-gray-500">Tire
                                                        Type</label>
                                                    <select id="tire_type_fl" name="tire_type_fl"
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                                        <option value="summer">Summer</option>
                                                        <option value="winter">Winter</option>
                                                        <option value="all-season">All-Season</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label for="tire_rim_status_fl"
                                                        class="block text-xs text-gray-500">Rim Status</label>
                                                    <select id="tire_rim_status_fl" name="tire_rim_status_fl"
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                                        <option value="original">Original</option>
                                                        <option value="aftermarket">Aftermarket</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Front Right Tire -->
                                        <div class="border p-3 rounded">
                                            <h4 class="font-medium mb-2">Front Right Tire</h4>
                                            <input type="hidden" name="tire_position_fr" value="right-front">
                                            <div class="space-y-2">
                                                <div>
                                                    <label for="tire_tread_depth_fr"
                                                        class="block text-xs text-gray-500">Tread Depth (mm)</label>
                                                    <input type="number" id="tire_tread_depth_fr" step="0.1"
                                                        name="tire_tread_depth_fr" min="0" max="20"
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                                </div>
                                                <div>
                                                    <label for="tire_rim_type_fr"
                                                        class="block text-xs text-gray-500">Rim Type</label>
                                                    <select id="tire_rim_type_fr" name="tire_rim_type_fr"
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                                        <option value="Steel">Steel</option>
                                                        <option value="Aluminum">Aluminum</option>
                                                        <option value="Alloy">Alloy</option>
                                                        <option value="Magnesium">Magnesium</option>
                                                        <option value="Carbon Fiber">Carbon Fiber</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label for="tire_type_fr" class="block text-xs text-gray-500">Tire
                                                        Type</label>
                                                    <select id="tire_type_fr" name="tire_type_fr"
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                                        <option value="summer">Summer</option>
                                                        <option value="winter">Winter</option>
                                                        <option value="all-season">All-Season</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label for="tire_rim_status_fr"
                                                        class="block text-xs text-gray-500">Rim Status</label>
                                                    <select id="tire_rim_status_fr" name="tire_rim_status_fr"
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                                        <option value="original">Original</option>
                                                        <option value="aftermarket">Aftermarket</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Rear Left Tire -->
                                        <div class="border p-3 rounded">
                                            <h4 class="font-medium mb-2">Rear Left Tire</h4>
                                            <input type="hidden" name="tire_position_rl" value="left-back">
                                            <div class="space-y-2">
                                                <div>
                                                    <label for="tire_tread_depth_rl"
                                                        class="block text-xs text-gray-500">Tread Depth (mm)</label>
                                                    <input type="number" id="tire_tread_depth_rl" step="0.1"
                                                        name="tire_tread_depth_rl" min="0" max="20"
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                                </div>
                                                <div>
                                                    <label for="tire_rim_type_rl"
                                                        class="block text-xs text-gray-500">Rim Type</label>
                                                    <select id="tire_rim_type_rl" name="tire_rim_type_rl"
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                                        <option value="Steel">Steel</option>
                                                        <option value="Aluminum">Aluminum</option>
                                                        <option value="Alloy">Alloy</option>
                                                        <option value="Magnesium">Magnesium</option>
                                                        <option value="Carbon Fiber">Carbon Fiber</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label for="tire_type_rl" class="block text-xs text-gray-500">Tire
                                                        Type</label>
                                                    <select id="tire_type_rl" name="tire_type_rl"
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                                        <option value="summer">Summer</option>
                                                        <option value="winter">Winter</option>
                                                        <option value="all-season">All-Season</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label for="tire_rim_status_rl"
                                                        class="block text-xs text-gray-500">Rim Status</label>
                                                    <select id="tire_rim_status_rl" name="tire_rim_status_rl"
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                                        <option value="original">Original</option>
                                                        <option value="aftermarket">Aftermarket</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Rear Right Tire -->
                                        <div class="border p-3 rounded">
                                            <h4 class="font-medium mb-2">Rear Right Tire</h4>
                                            <input type="hidden" name="tire_position_rr" value="right-back">
                                            <div class="space-y-2">
                                                <div>
                                                    <label for="tire_tread_depth_rr"
                                                        class="block text-xs text-gray-500">Tread Depth (mm)</label>
                                                    <input type="number" id="tire_tread_depth_rr" step="0.1"
                                                        name="tire_tread_depth_rr" min="0" max="20"
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                                </div>
                                                <div>
                                                    <label for="tire_rim_type_rr"
                                                        class="block text-xs text-gray-500">Rim Type</label>
                                                    <select id="tire_rim_type_rr" name="tire_rim_type_rr"
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                                        <option value="Steel">Steel</option>
                                                        <option value="Aluminum">Aluminum</option>
                                                        <option value="Alloy">Alloy</option>
                                                        <option value="Magnesium">Magnesium</option>
                                                        <option value="Carbon Fiber">Carbon Fiber</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label for="tire_type_rr" class="block text-xs text-gray-500">Tire
                                                        Type</label>
                                                    <select id="tire_type_rr" name="tire_type_rr"
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                                        <option value="summer">Summer</option>
                                                        <option value="winter">Winter</option>
                                                        <option value="all-season">All-Season</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label for="tire_rim_status_rr"
                                                        class="block text-xs text-gray-500">Rim Status</label>
                                                    <select id="tire_rim_status_rr" name="tire_rim_status_rr"
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                                        <option value="original">Original</option>
                                                        <option value="aftermarket">Aftermarket</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Save and Continue to Confirmation
                            </button>
                        </div>
                        </form>
                    </div>

                    <!-- Confirmation complete block -->
                    <div x-show="completedConfirmation" class="space-y-4">
                        <div class="text-center text-green-600 font-medium">Confirmation complete!</div>
                        <div class="flex justify-center">
                            <button @click="continueToContract()"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Continue to Contract Process
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Contract Process -->
                <div x-show="contractProcess === true" class="space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium">Contract Process</h3>
                        <button @click="contractProcess = false; completedConfirmation = true"
                            class="text-gray-600 hover:text-gray-800">← Back to Confirmation</button>
                    </div>

                    <!-- Registration Info Step -->
                    <div x-show="contractStep === 'registration'" class="space-y-4">
                        <h4 class="font-medium">Vehicle Registration Information</h4>
                        <form @submit.prevent="submitRegistrationData" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="first_registration"
                                        class="block text-sm font-medium text-gray-700">First Registration Date</label>
                                    <input type="date" id="first_registration"
                                        x-model="contractData.first_registration" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="purchase_price"
                                        class="block text-sm font-medium text-gray-700">Purchase Price</label>
                                    <input type="number" id="purchase_price" x-model="contractData.purchase_price"
                                        required min="0" step="0.01"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Continue to Contract Generation
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Contract Generation Step -->
                    <div x-show="contractStep === 'contract'" class="space-y-4">
                        <h4 class="font-medium">Procurement Contract</h4>
                        <div class="border p-4 rounded-lg space-y-4 bg-gray-50">
                            <div class="text-center">
                                <h5 class="text-lg font-bold">Vehicle Procurement Contract</h5>
                                <p class="text-sm text-gray-600">Contract Date: <span
                                        x-text="contractData.contract_date || ''"></span></p>
                            </div>
                            <div class="space-y-2">
                                <p><strong>Vehicle Information:</strong></p>
                                <p>VIN: <span x-text="contractData.vin || ''"></span></p>
                                <p>First Registration: <span x-text="contractData.first_registration || ''"></span></p>
                                <p>Purchase Price: <span
                                        x-text="contractData.purchase_price ? new Intl.NumberFormat('de-DE', { style: 'currency', currency: 'EUR' }).format(contractData.purchase_price) : '0,00 €'"></span>
                                </p>
                            </div>
                            <div class="space-y-2">
                                <p><strong>Terms and Conditions:</strong></p>
                                <p class="text-sm">This procurement contract confirms the purchase of the vehicle
                                    identified by the VIN above at the agreed purchase price. The vehicle's condition
                                    and specifications have been verified during the confirmation process.</p>
                            </div>
                            <div class="space-y-2">
                                <p><strong>Authorized Signature:</strong></p>
                                <div class="mb-2">
                                    <label for="employeeSelect"
                                        class="block text-sm font-medium text-gray-700">Employee Signing Contract</label>
                                    <select id="employeeSelect" x-model="selectedEmployeeId"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Select Employee</option>
                                    </select>
                                </div>
                                <div class="border bg-white rounded">
                                    <canvas id="signatureCanvas" class="w-full" height="150"></canvas>
                                </div>
                                <div class="flex justify-end">
                                    <button type="button" @click="clearSignature"
                                        class="text-blue-600 hover:text-blue-800 text-sm">
                                        Clear Signature
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" @click="submitContract"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Generate Contract
                            </button>
                        </div>
                    </div>

                    <!-- Purchase Log Step -->
                    <div x-show="contractStep === 'purchaseLog'" class="space-y-4">
                        <h4 class="font-medium">Purchase Log</h4>
                        <form @submit.prevent="submitPurchaseLog" class="space-y-4">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status Change</label>
                                    <input type="text" :value="purchaseLogStatusChange" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100">
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Submit Purchase Log
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Completion Step -->
                    <div x-show="contractStep === 'complete'" class="space-y-4">
                        <div class="text-center py-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-500 mx-auto mb-4"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <h4 class="text-xl font-medium text-green-600">Procurement Process Complete!</h4>
                            <p class="text-gray-600 mt-2">The vehicle has been successfully procured and is now part of
                                your inventory.</p>
                        </div>
                        <div class="flex justify-center">
                            <button @click="viewVehicle"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                View Vehicle
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sale Job -->
            <div x-show="selectedJob === 'sale'" x-cloak>
                <h2 class="text-xl font-semibold mb-4">Vehicle Sale Process</h2>
                <div class="space-y-4">
                    <p class="text-gray-600">Follow these steps to complete a vehicle sale:</p>
                    <ol class="list-decimal pl-5 space-y-2">
                        <li>Verify buyer information</li>
                        <li>Select vehicle from inventory</li>
                        <li>Prepare sales documentation</li>
                        <li>Process payment</li>
                        <li>Transfer vehicle ownership</li>
                        <li>Update inventory status</li>
                    </ol>
                    <div class="mt-6">
                        <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Start Sale Process
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('procurementData', () => ({
                showOptions: true,
                newVehicle: false,
                confirmation: false,
                completedConfirmation: false,
                contractProcess: false,
                contractStep: 'registration', // Initialize with default value
                contractData: {
                    vin: null,
                    first_registration: null,
                    purchase_price: 0,
                    contract_date: new Date().toISOString().split('T')[0],
                    signature: null
                },
                purchaseLogData: {
                    vin: null,
                    purchase_date: new Date().toISOString().split('T')[0],
                    purchase_price: 0,
                    purchased_from: '',
                    notes: ''
                },
                procurementContract: null,
                signaturePad: null,
                selectedVehicle: null,
                selectedManufacturer: null,
                showManufacturerModal: false,
                showModelModal: false,
                showSellerModal: false,
                showAddressModal: false,
                confirmationVin: null,
                originalVin: null,
                newManufacturer: {
                    name: '',
                    country_of_origin: ''
                },
                newModel: {
                    name: '',
                    year: new Date().getFullYear()
                },
                newSeller: {
                    first_name: '',
                    last_name: '',
                    gender: 'Male',
                    phone_number: '',
                    email: '',
                    address_id: '',
                    customer_type_id: ''
                },
                newAddress: {
                    street: '',
                    house_number: '',
                    city: '',
                    postal_code: '',
                    country: 'Germany'
                },
                selectedEmployeeId: '',
                employees: [],

                // Initialize the component
                init() {
                    console.log('Procurement component initialized');
                    this.showOptions = true;
                    this.newVehicle = false;
                    this.confirmation = false;
                    this.contractProcess = false;
                    this.contractStep = null;
                },

                // Start new vehicle process
                async startNewVehicle() {
                    this.completedConfirmation = false;
                    console.log('Starting new vehicle process...');
                    this.showOptions = false;
                    this.newVehicle = true;
                    await this.loadDropdownOptions();
                    this.setupMileageInput();
                },

                // Start confirmation process
                startConfirmation() {
                    this.completedConfirmation = false;
                    console.log('Starting confirmation process...');
                    this.showOptions = false;
                    this.confirmation = true;
                    // Initialize VIN state
                    this.confirmationVin = null;
                    this.originalVin = null;
                    // selectedVehicle will be set by handleVehicleSelect
                    this.selectedVehicle = null;
                },

                // Setup mileage input with step of 5000
                setupMileageInput() {
                    const mileageInput = document.querySelector('input[name="mileage"]');
                    if (mileageInput) {
                        mileageInput.step = "5000";
                        mileageInput.addEventListener('change', function() {
                            this.value = Math.round(this.value / 5000) * 5000;
                        });
                    }
                },

                // Load all dropdown options
                async loadDropdownOptions() {
                    try {
                        console.log('Loading dropdown options...');

                        const manufacturers = await this.fetchData('/api/manufacturers');
                        this.populateSelect('manufacturer', manufacturers);

                        const vehicleTypes = await this.fetchData('/api/vehicle-types');
                        this.populateSelect('vehicle_type', vehicleTypes);

                        const fuelTypes = await this.fetchData('/api/fuel-types');
                        this.populateSelect('fuel_type', fuelTypes);

                        const transmissions = await this.fetchData('/api/transmissions');
                        this.populateSelect('transmission', transmissions);

                        const driveTypes = await this.fetchData('/api/drive-types');
                        this.populateSelect('drive_type', driveTypes);

                        console.log('All dropdowns loaded successfully');
                    } catch (error) {
                        console.error('Error loading dropdown options:', error);
                    }
                },

                // Fetch data helper
                async fetchData(url) {
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (!response.ok) throw new Error(`Failed to fetch from ${url}`);
                    return response.json();
                },

                // Populate select helper
                populateSelect(selectName, options) {
                    const select = document.querySelector(`select[name="${selectName}"]`);
                    if (!select) return;

                    select.innerHTML = '<option value="">Select ' +
                        selectName.charAt(0).toUpperCase() + selectName.slice(1) + '</option>';

                    options.forEach(option => {
                        const opt = document.createElement('option');
                        switch (selectName) {
                            case 'manufacturer':
                                opt.value = option.manufacturer_id;
                                opt.textContent = option.name;
                                break;
                            case 'vehicle_type':
                                opt.value = option.type_id;
                                opt.textContent = option.type_name;
                                break;
                            case 'fuel_type':
                                opt.value = option.fuel_type_id;
                                opt.textContent = option.fuel_name;
                                break;
                            case 'transmission':
                                opt.value = option.transmission_id;
                                opt.textContent = option.type;
                                break;
                            case 'drive_type':
                                opt.value = option.drive_type_id;
                                opt.textContent = option.drive_type_name;
                                break;
                            default:
                                opt.value = option.id || '';
                                opt.textContent = option.name || '';
                        }
                        select.appendChild(opt);
                    });
                },

                // Handle manufacturer change
                async handleManufacturerChange(event) {
                    const manufacturerId = event.target.value;
                    this.selectedManufacturer = manufacturerId;
                    const modelSelect = document.querySelector('select[name="model"]');
                    modelSelect.disabled = true;
                    modelSelect.innerHTML = '<option value="">Loading models...</option>';

                    if (manufacturerId) {
                        try {
                            const models = await this.fetchData(
                                `/api/vehicle-models/by-manufacturer/${manufacturerId}`);
                            modelSelect.innerHTML = '<option value="">Select Model</option>';
                            models.forEach(model => {
                                const option = document.createElement('option');
                                option.value = model.model_id;
                                option.textContent = model.model_name;
                                modelSelect.appendChild(option);
                            });
                            modelSelect.disabled = false;
                        } catch (error) {
                            console.error('Error loading models:', error);
                            modelSelect.innerHTML =
                                '<option value="">Error loading models</option>';
                        }
                    }
                },

                async addNewManufacturer() {
                    try {
                        const response = await fetch('/api/manufacturers', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(this.newManufacturer)
                        });

                        if (!response.ok) throw new Error('Failed to add manufacturer');

                        const manufacturer = await response.json();
                        this.showManufacturerModal = false;

                        // Add new manufacturer to dropdown and select it
                        const select = document.querySelector('select[name="manufacturer"]');
                        const option = document.createElement('option');
                        option.value = manufacturer.id;
                        option.textContent = manufacturer.name;
                        select.appendChild(option);
                        select.value = manufacturer.id;

                        // Trigger manufacturer change to enable model selection
                        this.selectedManufacturer = manufacturer.id;
                        await this.handleManufacturerChange({
                            target: {
                                value: manufacturer.id
                            }
                        });

                        this.newManufacturer = {
                            name: '',
                            country_of_origin: ''
                        };
                    } catch (error) {
                        console.error('Error adding manufacturer:', error);
                        alert('Failed to add manufacturer. Please try again.');
                    }
                },

                async addNewModel() {
                    if (!this.selectedManufacturer) {
                        alert('Please select a manufacturer first');
                        return;
                    }

                    try {
                        const modelData = {
                            model_name: this.newModel.name,
                            manufacturer_id: this.selectedManufacturer
                        };

                        console.log('Adding new model:', modelData);


                        const response = await fetch('/api/vehicle-models', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(modelData)
                        });

                        if (!response.ok) throw new Error('Failed to add model');

                        const model = await response.json();
                        this.showModelModal = false;

                        // Add new model to dropdown and select it
                        const select = document.querySelector('select[name="model"]');
                        const option = document.createElement('option');
                        option.value = model.model_id;
                        option.textContent = model.model_name;
                        select.appendChild(option);
                        select.value = model.model_id;

                        // Reset form
                        this.newModel = {
                            name: ''
                        };
                    } catch (error) {
                        console.error('Error adding model:', error);
                        alert('Failed to add model. Please try again.');
                    }
                },

                async addNewSeller() {
                    try {
                        const response = await fetch('/api/sellers', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(this.newSeller)
                        });

                        if (!response.ok) throw new Error('Failed to add seller');

                        const seller = await response.json();
                        this.showSellerModal = false;

                        // Add new seller to dropdown and select it
                        const select = document.getElementById('sellerSelect');
                        const option = document.createElement('option');
                        option.value = seller.seller_id;
                        option.textContent = `${seller.first_name} ${seller.last_name}`;
                        select.appendChild(option);
                        select.value = seller.seller_id;

                        // Reset form
                        this.newSeller = {
                            first_name: '',
                            last_name: '',
                            gender: 'Male',
                            phone_number: '',
                            email: '',
                            address_id: '',
                            customer_type_id: ''
                        };
                    } catch (error) {
                        console.error('Error adding seller:', error);
                        alert('Failed to add seller. Please try again.');
                    }
                },

                async addNewAddress() {
                    try {
                        const response = await fetch('/api/addresses', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(this.newAddress)
                        });

                        if (!response.ok) throw new Error('Failed to add address');

                        const address = await response.json();
                        this.showAddressModal = false;

                        // Add new address to dropdown and select it
                        const select = document.getElementById('seller_address');
                        const option = document.createElement('option');
                        option.value = address.address_id;
                        option.textContent = `${address.street}, ${address.city}`;
                        select.appendChild(option);
                        select.value = address.address_id;

                        // Update the newSeller object with the newly created address
                        this.newSeller.address_id = address.address_id;

                        // Reset form
                        this.newAddress = {
                            street: '',
                            house_number: '',
                            city: '',
                            postal_code: '',
                            country: 'Germany'
                        };
                    } catch (error) {
                        console.error('Error adding address:', error);
                        alert('Failed to add address. Please try again.');
                    }
                },

                // Convert HP to KW
                updateKW(event) {
                    const hp = parseFloat(event.target.value) || 0;
                    const kw = Math.round(hp * 0.7457);
                    document.querySelector('input[name="engine_power_kw"]').value = kw;
                },

                // Convert KW to HP
                updateHP(event) {
                    const kw = parseFloat(event.target.value) || 0;
                    const hp = Math.round(kw / 0.7457);
                    document.querySelector('input[name="engine_power_hp"]').value = hp;
                },

                // VIN validation helper
                validateVIN(vin) {
                    // VIN must be exactly 17 characters
                    if (vin.length !== 17) return false;

                    // VIN can only contain alphanumeric characters (excluding I, O, Q)
                    const validChars = /^[A-HJ-NPR-Z0-9]+$/;
                    return validChars.test(vin);
                },

                // Handle vehicle selection for confirmation
                handleVehicleSelect(event) {
                    const vin = event.target.value;
                    if (!vin) {
                        this.selectedVehicle = null;
                        return;
                    }

                    fetch(`/api/vehicles/${vin}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Failed to fetch vehicle details');
                            return response.json();
                        })
                        .then(vehicle => {
                            this.selectedVehicle = vehicle;
                            this.originalVin = vehicle.vin;
                            // Pre-fill the VIN field
                            document.querySelector('input[name="vin"]').value = vehicle.vin;
                            console.log('Selected vehicle:', vehicle);
                            // Populate VIN field
                            if (vehicle.vin) {
                                this.confirmationVin = vehicle.vin;
                                document.querySelector('input[name="vin"]').value = vehicle.vin;
                            }

                            // Populate seller if exists
                            if (vehicle.seller_id) {
                                document.querySelector('select[name="seller_id"]').value = vehicle
                                    .seller_id;
                            }

                            // Populate warranty status if exists
                            if (vehicle.warranty_status) {
                                document.querySelector('select[name="warranty_status"]').value =
                                    vehicle.warranty_status;
                            }

                            // Populate inspection status if exists
                            if (vehicle.inspection_status) {
                                document.querySelector('select[name="inspection_status"]').value =
                                    vehicle.inspection_status;
                            }

                            // Populate previous owners if exists
                            if (vehicle.num_previous_owners) {
                                document.querySelector('input[name="previous_owners"]').value =
                                    vehicle.num_previous_owners;
                            }

                            // Populate tire information if exists
                            if (vehicle.tires) {
                                vehicle.tires.forEach(tire => {
                                    const positionMap = {
                                        'left-front': 'fl',
                                        'right-front': 'fr',
                                        'left-back': 'rl',
                                        'right-back': 'rr'
                                    };

                                    const position = positionMap[tire.position];
                                    if (!position) return;

                                    const inputs = {
                                        tread_depth: document.querySelector(
                                            `input[name="tire_tread_depth_${position}"]`
                                        ),
                                        rim_type: document.querySelector(
                                            `select[name="tire_rim_type_${position}"]`
                                        ),
                                        tire_type: document.querySelector(
                                            `select[name="tire_type_${position}"]`),
                                        rim_status: document.querySelector(
                                            `select[name="tire_rim_status_${position}"]`
                                        )
                                    };

                                    Object.entries(inputs).forEach(([key, element]) => {
                                        if (tire[key] && element) {
                                            element.value = tire[key];
                                        }
                                    });
                                });
                            }

                            // Populate damage records if they exist
                            if (vehicle.damage_records && vehicle.damage_records.length > 0) {
                                const damageContainer = document.getElementById('damageRecords');
                                if (damageContainer) {
                                    damageContainer.innerHTML = ''; // Clear existing damages
                                    vehicle.damage_records.forEach(damage => {
                                        addDamageRecord(); // Use correct function name
                                        const index = damageContainer.children.length - 1;
                                        const currentDamage = damageContainer.children[
                                            index];

                                        if (currentDamage) {
                                            if (damage.description) {
                                                currentDamage.querySelector(
                                                    `input[name="damages[${index}][damage_description]"]`
                                                ).value = damage.description;
                                            }
                                            if (damage.damage_type) {
                                                currentDamage.querySelector(
                                                    `select[name="damages[${index}][damage_type]"]`
                                                ).value = damage.damage_type;
                                            }
                                            if (damage.location) {
                                                currentDamage.querySelector(
                                                    `select[name="damages[${index}][location]"]`
                                                ).value = damage.location;
                                            }
                                            if (damage.cost) {
                                                currentDamage.querySelector(
                                                    `input[name="damages[${index}][repair_cost]"]`
                                                ).value = damage.cost;
                                            }
                                        }
                                    });
                                }
                            }

                            // Populate additional equipment if they exist
                            if (vehicle.additional_equipment && vehicle.additional_equipment
                                .length > 0) {
                                const equipmentContainer = document.getElementById(
                                    'additionalEquipment');
                                if (equipmentContainer) {
                                    equipmentContainer.innerHTML = ''; // Clear existing equipment
                                    vehicle.additional_equipment.forEach(equip => {
                                        addEquipment(); // Add new equipment form
                                        const index = equipmentContainer.children.length -
                                            1;
                                        const currentEquipment = equipmentContainer
                                            .children[index];

                                        if (currentEquipment) {
                                            if (equip.equipment_description) {
                                                currentEquipment.querySelector(
                                                        `input[name="equipment[${index}][name]"]`
                                                    ).value = equip
                                                    .equipment_description;
                                            }
                                            if (equip.condition) {
                                                currentEquipment.querySelector(
                                                    `select[name="equipment[${index}][condition]"]`
                                                ).value = equip.condition;
                                            }
                                        }
                                    });
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Failed to load vehicle details');
                        });
                },

                // Handle form submission
                async submitNewVehicle(event) {
                    event.preventDefault();
                    const formData = new FormData(event.target);

                    // Generate a unique VIN if not provided
                    const vin = formData.get('vin') || ('TEMP' + Date.now().toString(36)
                        .toUpperCase());

                    if (formData.get('vin') && !this.validateVIN(formData.get('vin'))) {
                        alert(
                            'Invalid VIN format. VIN must be 17 characters long and contain only alphanumeric characters (excluding I, O, Q).'
                        );
                        return;
                    }

                    const vehicleData = {
                        vin: vin,
                        manufacturer_id: formData.get('manufacturer'),
                        model_id: formData.get('model'),
                        first_registration: null, // Will be set during confirmation
                        mileage: formData.get('mileage'),
                        transmission_id: formData.get('transmission'),
                        type_id: formData.get('vehicle_type'),
                        drive_type_id: formData.get('drive_type'),
                        color: formData.get('color'),
                        purchase_price: formData.get('price'),
                        selling_price: 0, // Will be set later
                        num_previous_owners: 0, // Will be set during confirmation
                        owner_type_id: 1, // Default owner type, will be updated during confirmation
                        general_inspection_next_due_date: null, // Will be set during confirmation
                        evaluation_date: new Date().toISOString().split('T')[0],
                        last_edited_date: new Date().toISOString().split('T')[0],
                        days_on_stock: 0,
                        status: 'Pending',
                        additional_info: ''
                    };

                    console.log("vehicle date: ", vehicleData);


                    try {
                        // First save the vehicle
                        const vehicleResponse = await fetch('/api/vehicles', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(vehicleData)
                        });

                        if (!vehicleResponse.ok) {
                            const errorData = await vehicleResponse.json();
                            throw new Error(errorData.message || 'Failed to save vehicle');
                        }

                        const vehicle = await vehicleResponse.json();

                        // Then save the engine specification
                        const engineData = {
                            vin: vehicle.vin,
                            hp: formData.get('engine_power_hp'),
                            kw: formData.get('engine_power_kw'),
                            ccm: formData.get('engine_displacement'),
                            fuel_type_id: formData.get('fuel_type')
                        };

                        const engineResponse = await fetch('/api/engine-specifications', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(engineData)
                        });

                        if (!engineResponse.ok) {
                            throw new Error('Failed to save engine specification');
                        }

                        // Move to confirmation process with the saved vehicle
                        this.selectedVehicle = vehicle;
                        this.newVehicle = false;
                        this.confirmation = true;
                        // Initialize VIN state for new vehicle confirmation
                        this.confirmationVin = vehicle.vin;
                        this.originalVin = vehicle.vin;
                    } catch (error) {
                        console.error('Error saving vehicle:', error);
                        alert('Error saving vehicle: ' + error.message);
                    }
                },

                // Handle confirmation form submission
                async submitConfirmation(event) {
                    event.preventDefault();
                    const formData = new FormData(event.target);
                    if (!this.selectedVehicle) {
                        alert('No vehicle selected');
                        return;
                    }

                    // Extract seller selection
                    const sellerId = formData.get('seller_id');

                    try {
                        // Extract tire information
                        const tires = [{
                                position: formData.get('tire_position_fl'),
                                rim_status: formData.get('tire_rim_status_fl'),
                                rim_type: formData.get('tire_rim_type_fl'),
                                tire_type: formData.get('tire_type_fl'),
                                tread_depth: formData.get('tire_tread_depth_fl')
                            },
                            {
                                position: formData.get('tire_position_fr'),
                                rim_status: formData.get('tire_rim_status_fr'),
                                rim_type: formData.get('tire_rim_type_fr'),
                                tire_type: formData.get('tire_type_fr'),
                                tread_depth: formData.get('tire_tread_depth_fr')
                            },
                            {
                                position: formData.get('tire_position_rl'),
                                rim_status: formData.get('tire_rim_status_rl'),
                                rim_type: formData.get('tire_rim_type_rl'),
                                tire_type: formData.get('tire_type_rl'),
                                tread_depth: formData.get('tire_tread_depth_rl')
                            },
                            {
                                position: formData.get('tire_position_rr'),
                                rim_status: formData.get('tire_rim_status_rr'),
                                rim_type: formData.get('tire_rim_type_rr'),
                                tire_type: formData.get('tire_type_rr'),
                                tread_depth: formData.get('tire_tread_depth_rr')
                            }
                        ];

                        // Extract damage records
                        const damages = [];
                        document.querySelectorAll('#damageRecords > div').forEach((element,
                            index) => {
                            damages.push({
                                vin: this.selectedVehicle.vin,
                                damage_date: formData.get(
                                    `damages[${index}][damage_date]`),
                                damage_type: formData.get(
                                    `damages[${index}][damage_type]`),
                                location: formData.get(
                                    `damages[${index}][location]`),
                                description: formData.get(
                                    `damages[${index}][damage_description]`),
                                cost: formData.get(
                                    `damages[${index}][repair_cost]`) || null,
                                repair_status: formData.get(
                                    `damages[${index}][repair_status]`),
                                repair_date: null // Will be set when repair is completed
                            });
                        });

                        // Extract additional equipment
                        const equipment = [];
                        document.querySelectorAll('#additionalEquipment > div').forEach((element,
                            index) => {
                            equipment.push({
                                vin: this.selectedVehicle.vin,
                                name: formData.get(`equipment[${index}][name]`),
                                condition: formData.get(
                                    `equipment[${index}][condition]`)
                            });
                        });

                        // Prepare confirmation data
                        const confirmationData = {
                            original_vin: formData.get('original_vin'),
                            vin: formData.get('vin'),
                            num_previous_owners: formData.get('previous_owners'),
                            warranty_status: formData.get('warranty_status'),
                            inspection_status: formData.get('inspection_status'),
                            seller_id: sellerId,
                            tires_information: tires,
                            damages: damages,
                            equipment: equipment
                        };
                        console.log('Confirmation data:', confirmationData);

                        const confirmationResponse = await fetch('/api/vehicle-confirmations', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(confirmationData)
                        });

                        if (!confirmationResponse.ok) {
                            const errorData = await confirmationResponse.json();
                            throw new Error(errorData.message);
                        }

                        // change the selectedVehicle vin to the new vin
                        this.selectedVehicle.vin = formData.get('vin');
                        this.selectedVehicle.seller_id = sellerId;

                        // Update the vehicle status from "Pending" to "Available" after confirmation
                        const updateVehicleStatus = await fetch(
                            `/api/vehicles/${this.selectedVehicle.vin}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    status: 'Available'
                                })
                            });

                        if (!updateVehicleStatus.ok) {
                            console.error('Warning: Failed to update vehicle status to Available');
                        } else {
                            console.log('Vehicle status updated to Available');
                        }

                        this.completedConfirmation = true;
                        this.confirmation = false;
                        this.showOptions = false;
                        console.log('Confirmation data saved successfully');


                    } catch (error) {
                        console.error('Error saving confirmation data(try catch):', error);
                        alert('Error saving confirmation data(try catch): ' + error.message);
                    }
                },

                // Continue to contract process
                async continueToContract() {
                    console.log('Continue to contract process');
                    this.completedConfirmation = false;
                    this.contractProcess = true;
                    this.contractStep = 'registration';

                    // Initialize the contract data
                    this.contractData = {
                        vin: this.selectedVehicle ? this.selectedVehicle.vin : null,
                        first_registration: this.selectedVehicle ? this.selectedVehicle
                            .first_registration || null : null,
                        purchase_price: this.selectedVehicle ? this.selectedVehicle
                            .purchase_price || 0 : 0,
                        contract_date: new Date().toISOString().split('T')[0],
                        signature: null
                    };

                    this.purchaseLogData = {
                        vin: this.selectedVehicle ? this.selectedVehicle.vin : null,
                        purchase_date: new Date().toISOString().split('T')[0],
                        purchase_price: this.selectedVehicle ? this.selectedVehicle
                            .purchase_price || 0 : 0,
                        purchased_from: '',
                        notes: ''
                    };

                    await this.loadEmployees();
                },

                // Submit vehicle registration and purchase price
                async submitRegistrationData() {
                    try {
                        console.log('Submitting registration data...');
                        if (!this.contractData.first_registration) {
                            alert('Please enter the first registration date');
                            return;
                        }

                        if (!this.contractData.purchase_price || this.contractData.purchase_price <=
                            0) {
                            alert('Please enter a valid purchase price');
                            return;
                        }

                        // Update the vehicle with registration date and purchase price
                        const response = await fetch(`/api/vehicles/${this.contractData.vin}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                first_registration: this.contractData
                                    .first_registration,
                                purchase_price: this.contractData.purchase_price
                            })
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message ||
                                'Failed to update vehicle registration information');
                        }

                        console.log('Registration data saved successfully');

                        // Move to contract generation step
                        this.contractStep = 'contract';

                        // Initialize signature pad when moving to contract step
                        setTimeout(() => {
                            this.initSignaturePad();
                        }, 100);
                    } catch (error) {
                        console.error('Error updating vehicle registration:', error);
                        alert('Error: ' + error.message);
                    }
                },

                // Initialize signature pad
                initSignaturePad() {
                    setTimeout(() => {
                        const canvas = document.getElementById('signatureCanvas');
                        if (canvas) {
                            console.log('Initializing signature pad...');
                            this.signaturePad = new SignaturePad(canvas, {
                                backgroundColor: 'rgb(255, 255, 255)',
                                penColor: 'rgb(0, 0, 0)'
                            });

                            // Adjust canvas size
                            canvas.width = canvas.offsetWidth;
                            canvas.height = canvas.offsetHeight;
                        }
                    }, 100);
                },

                // Clear signature pad
                clearSignature() {
                    if (this.signaturePad) {
                        this.signaturePad.clear();
                    }
                },

                // Load employees for contract signing
                async loadEmployees() {
                    try {
                        const response = await fetch('/api/employees', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        if (!response.ok) throw new Error('Failed to fetch employees');
                        this.employees = await response.json();
                        // Populate the select
                        const select = document.getElementById('employeeSelect');
                        if (select) {
                            select.innerHTML = '<option value="">Select Employee</option>';
                            this.employees.forEach(emp => {
                                const option = document.createElement('option');
                                option.value = emp.employee_id;
                                option.textContent = emp.first_name + ' ' + emp.last_name;
                                select.appendChild(option);
                            });
                        }
                    } catch (e) {
                        console.error(e);
                    }
                },

                // Submit contract with signature
                async submitContract() {
                    try {
                        if (!this.signaturePad || this.signaturePad.isEmpty()) {
                            alert('Please provide a signature');
                            return;
                        }
                        if (!this.selectedEmployeeId) {
                            alert('Please select an employee to sign the contract');
                            return;
                        }

                        // Convert signature to data URL
                        this.contractData.signature = this.signaturePad.toDataURL();

                        console.log('contract details', this.contractData);

                        // Create procurement contract
                        const contractResponse = await fetch('/api/procurement-contracts', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                vin: this.contractData.vin,
                                contract_date: this.contractData.contract_date,
                                contract_price: this.contractData.purchase_price,
                                signature: this.contractData.signature,
                                employee_id: this.selectedEmployeeId,
                                seller_id: this.selectedVehicle && this.selectedVehicle.seller_id ?
                                    this.selectedVehicle.seller_id : null
                            })
                        });

                        if (!contractResponse.ok) {
                            throw new Error('Failed to create procurement contract');
                        }

                        const contract = await contractResponse.json();
                        this.procurementContract = contract;

                        // Move to purchase log step
                        this.contractStep = 'purchaseLog';
                    } catch (error) {
                        console.error('Error creating procurement contract:', error);
                        alert('Error: ' + error.message);
                    }
                },

                // Submit purchase log
                async submitPurchaseLog() {
                    try {
                        const vin = this.selectedVehicle?.vin;
                        const employee_id = this.selectedEmployeeId;
                        const seller_id = this.selectedVehicle?.seller_id;
                        if (!vin || !employee_id || !seller_id) {
                            alert('Missing required data for purchase log.');
                            return;
                        }
                        const logResponse = await fetch('/api/purchase-logs', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                vin,
                                employee_id,
                                seller_id
                            })
                        });
                        if (!logResponse.ok) {
                            throw new Error('Failed to create purchase log');
                        }
                        this.contractStep = 'complete';
                    } catch (error) {
                        console.error('Error creating purchase log:', error);
                        alert('Error: ' + error.message);
                    }
                },

                // Navigate to vehicle visualizer
                viewVehicle() {
                    window.location.href = `/vehicles`;
                },

                async initNewVehicle() {
                    console.log('Initializing new vehicle form...');
                    await this.loadDropdownOptions();

                    // Reset the form fields
                    const form = document.getElementById('newVehicleForm');
                    if (form) form.reset();

                    // Reset dropdowns
                    this.selectedManufacturer = null;
                    const modelSelect = document.querySelector('select[name="model"]');
                    if (modelSelect) {
                        modelSelect.innerHTML = '<option value="">Select Model</option>';
                        modelSelect.disabled = true;
                    }

                    // Set mileage step
                    const mileageInput = document.querySelector('input[name="mileage"]');
                    if (mileageInput) {
                        mileageInput.step = "5000";
                        mileageInput.addEventListener('change', function() {
                            this.value = Math.round(this.value / 5000) * 5000;
                        });
                    }
                },

                purchaseLogStatusChange() {
                    if (!this.selectedVehicle || !this.selectedVehicle.vin || !this.selectedVehicle.seller) return '';
                    return `vehicle ${this.selectedVehicle.vin} purchased from seller ${this.selectedVehicle.seller.first_name} ${this.selectedVehicle.seller.last_name}`;
                },
            }));
        });

        // Load all dropdown options when the page loads
        document.addEventListener('DOMContentLoaded', () => {
            loadVehiclesForConfirmation();
            loadSellersForConfirmation();

            // Load customer types for seller form
            fetch('/api/customer-types', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('seller_type');
                    select.innerHTML = '<option value="">Select Type</option>';
                    data.forEach(type => {
                        const option = document.createElement('option');
                        option.value = type.customer_type_id;
                        option.textContent = type.type_name;
                        select.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading customer types:', error);
                });

            // Load addresses for seller form
            fetch('/api/addresses', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('seller_address');
                    select.innerHTML = '<option value="">Select Address</option>';
                    data.forEach(address => {
                        const option = document.createElement('option');
                        option.value = address.address_id;
                        option.textContent = `${address.street}, ${address.city}`;
                        select.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading addresses:', error);
                });
        });

        // Load vehicles for confirmation process
        async function loadVehiclesForConfirmation() {
            try {
                const response = await fetch('/api/vehicles/needs-confirmation');
                console.log('Loading vehicles for confirmation...');
                if (!response.ok) throw new Error('Failed to fetch vehicles');
                // Parse the response as JSON
                // Check if the response is empty
                if (response.status === 204) {
                    console.log('No vehicles found for confirmation');
                    return;
                }
                // Parse the response as JSON
                console.log('Parsing vehicles response...');
                const vehicles = await response.json();
                const select = document.getElementById('vehicleSelect');

                vehicles.forEach(vehicle => {
                    const option = document.createElement('option');
                    option.value = vehicle.vin;
                    option.textContent =
                        `${vehicle.vin} - ${vehicle.manufacturer?.name} ${vehicle.model?.name}`;
                    select.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading vehicles:', error);
            }
        }

        // Load sellers for confirmation process
        async function loadSellersForConfirmation() {
            try {
                const response = await fetch('/api/sellers', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const sellers = await response.json();
                const select = document.getElementById('sellerSelect');
                sellers.forEach(seller => {
                    const option = document.createElement('option');
                    option.value = seller.seller_id;
                    option.textContent = `${seller.first_name} ${seller.last_name}`;
                    select.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading sellers:', error);
            }
        }

        // Dynamic damage record addition
        function addDamageRecord() {
            const container = document.getElementById('damageRecords');
            const index = container.children.length;

            const recordDiv = document.createElement('div');
            recordDiv.className = 'grid grid-cols-2 gap-4 p-4 border rounded-lg';
            recordDiv.innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <input type="text" name="damages[${index}][damage_description]" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Type</label>
                    <select name="damages[${index}][damage_type]" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="Scruff">Scruff</option>
                        <option value="Dent">Dent</option>
                        <option value="Damage">Damage</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Location</label>
                    <select name="damages[${index}][location]" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="Front">Front</option>
                        <option value="Rear">Rear</option>
                        <option value="Driver Side">Driver Side</option>
                        <option value="Passenger Side">Passenger Side</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Estimated Repair Cost</label>
                    <input type="number" name="damages[${index}][repair_cost]" min="0" step="0.01"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            `;
            container.appendChild(recordDiv);
        }

        // Dynamic equipment addition
        function addEquipment() {
            const container = document.getElementById('additionalEquipment');
            const index = container.children.length;

            const equipDiv = document.createElement('div');
            equipDiv.className = 'grid grid-cols-2 gap-4 p-4 border rounded-lg';
            equipDiv.innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700">Equipment Name</label>
                    <input type="text" name="equipment[${index}][name]" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Condition</label>
                    <select name="equipment[${index}][condition]" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="new">New</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                    </select>
                </div>
            `;
            container.appendChild(equipDiv);
        }
    </script>
</body>

</html>
