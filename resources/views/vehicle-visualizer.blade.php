<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vehicle Visualizer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .container {
            margin-top: 20px;
        }

        .card {
            margin-bottom: 20px;
        }

        #vehicleForm,
        #vehicleDetails {
            display: none;
        }

        .loading {
            display: none;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        .success-message {
            color: green;
            margin-top: 10px;
        }

        .tab-content {
            padding: 20px 0;
        }

        .status-available {
            color: green;
        }

        .status-sold {
            color: blue;
        }

        .status-reserved {
            color: orange;
        }

        .status-in-service {
            color: purple;
        }

        .status-scrapped {
            color: red;
        }

        .badge {
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Vehicle Visualizer</h1>
        <div class="row">
            <div class="col-md-6 mb-3">
                <button id="showFormBtn" class="btn btn-primary">Add New Vehicle</button>
                <a href="/" class="btn btn-secondary">Back to Home</a>
            </div>
            <div class="col-md-6 mb-3">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search vehicles...">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Vehicle Form -->
        <div class="card" id="vehicleForm">
            <div class="card-header">
                <h5 id="formTitle">Add New Vehicle</h5>
            </div>
            <div class="card-body">
                <form id="vehicleDataForm">
                    <input type="hidden" id="vehicleId">

                    <ul class="nav nav-tabs" id="formTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic"
                                type="button" role="tab">Basic Info</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="technical-tab" data-bs-toggle="tab" data-bs-target="#technical"
                                type="button" role="tab">Technical Details</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="ownership-tab" data-bs-toggle="tab" data-bs-target="#ownership"
                                type="button" role="tab">Ownership</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="additional-tab" data-bs-toggle="tab"
                                data-bs-target="#additional" type="button" role="tab">Additional Info</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="formTabContent">
                        <!-- Basic Info Tab -->
                        <div class="tab-pane fade show active" id="basic" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="vin" class="form-label">VIN</label>
                                    <input type="text" class="form-control" id="vin" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="license_plate" class="form-label">License Plate</label>
                                    <input type="text" class="form-control" id="license_plate">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="manufacturer_id" class="form-label">Manufacturer</label>
                                    <select class="form-select" id="manufacturer_id" required>
                                        <option value="">Select Manufacturer</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="model_id" class="form-label">Model</label>
                                    <select class="form-select" id="model_id" required>
                                        <option value="">Select Model</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="year" class="form-label">Year</label>
                                    <input type="number" class="form-control" id="year" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="color" class="form-label">Color</label>
                                    <input type="text" class="form-control" id="color" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" required>
                                        <option value="available">Available</option>
                                        <option value="sold">Sold</option>
                                        <option value="reserved">Reserved</option>
                                        <option value="in-service">In Service</option>
                                        <option value="scrapped">Scrapped</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Technical Details Tab -->
                        <div class="tab-pane fade" id="technical" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="vehicle_type_id" class="form-label">Vehicle Type</label>
                                    <select class="form-select" id="vehicle_type_id">
                                        <option value="">Select Vehicle Type</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="drive_type_id" class="form-label">Drive Type</label>
                                    <select class="form-select" id="drive_type_id">
                                        <option value="">Select Drive Type</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fuel_type_id" class="form-label">Fuel Type</label>
                                    <select class="form-select" id="fuel_type_id">
                                        <option value="">Select Fuel Type</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="transmission_id" class="form-label">Transmission</label>
                                    <select class="form-select" id="transmission_id">
                                        <option value="">Select Transmission</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="mileage" class="form-label">Mileage (km)</label>
                                    <input type="number" class="form-control" id="mileage">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="engine_size" class="form-label">Engine Size (cc)</label>
                                    <input type="number" class="form-control" id="engine_size">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="power" class="form-label">Power (hp)</label>
                                    <input type="number" class="form-control" id="power">
                                </div>
                            </div>
                        </div>

                        <!-- Ownership Tab -->
                        <div class="tab-pane fade" id="ownership" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="owner_type_id" class="form-label">Owner Type</label>
                                    <select class="form-select" id="owner_type_id">
                                        <option value="">Select Owner Type</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="seller_id" class="form-label">Seller</label>
                                    <select class="form-select" id="seller_id">
                                        <option value="">Select Seller</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="purchase_date" class="form-label">Purchase Date</label>
                                    <input type="date" class="form-control" id="purchase_date">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="purchase_price" class="form-label">Purchase Price</label>
                                    <input type="number" step="0.01" class="form-control" id="purchase_price">
                                </div>
                            </div>
                        </div>

                        <!-- Additional Info Tab -->
                        <div class="tab-pane fade" id="additional" role="tabpanel">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" rows="3"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="list_price" class="form-label">List Price</label>
                                    <input type="number" step="0.01" class="form-control" id="list_price">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="features" class="form-label">Features</label>
                                    <input type="text" class="form-control" id="features"
                                        placeholder="Comma separated list">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" rows="2"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Save Vehicle</button>
                        <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
                    </div>
                    <div class="loading mt-3">Saving vehicle data...</div>
                    <div class="error-message"></div>
                    <div class="success-message"></div>
                </form>
            </div>
        </div>

        <!-- Vehicle Details -->
        <div class="card" id="vehicleDetails">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Vehicle Details</h5>
                <div>
                    <button id="editVehicleBtn" class="btn btn-sm btn-primary">Edit</button>
                    <button id="backToListBtn" class="btn btn-sm btn-secondary">Back to List</button>
                </div>
            </div>
            <div class="card-body">
                <div class="loading">Loading vehicle details...</div>
                <div class="error-message"></div>

                <ul class="nav nav-tabs" id="detailTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab"
                            data-bs-target="#overview" type="button" role="tab">Overview</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs"
                            type="button" role="tab">Specifications</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history"
                            type="button" role="tab">History</button>
                    </li>
                </ul>

                <div class="tab-content" id="detailTabContent">
                    <!-- Overview Tab -->
                    <div class="tab-pane fade show active" id="overview" role="tabpanel">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h4 id="detailTitle">Vehicle Details</h4>
                                <p><strong>VIN:</strong> <span id="detailVin"></span></p>
                                <p><strong>License Plate:</strong> <span id="detailLicensePlate"></span></p>
                                <p><strong>Status:</strong> <span id="detailStatus"></span></p>
                                <p><strong>Year:</strong> <span id="detailYear"></span></p>
                                <p><strong>Color:</strong> <span id="detailColor"></span></p>
                                <p><strong>List Price:</strong> <span id="detailListPrice"></span></p>
                            </div>
                            <div class="col-md-6">
                                <h4>Description</h4>
                                <p id="detailDescription"></p>
                                <h5>Features</h5>
                                <p id="detailFeatures"></p>
                                <p><strong>Notes:</strong> <span id="detailNotes"></span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Specifications Tab -->
                    <div class="tab-pane fade" id="specs" role="tabpanel">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h4>Technical Specifications</h4>
                                <p><strong>Vehicle Type:</strong> <span id="detailVehicleType"></span></p>
                                <p><strong>Drive Type:</strong> <span id="detailDriveType"></span></p>
                                <p><strong>Fuel Type:</strong> <span id="detailFuelType"></span></p>
                                <p><strong>Transmission:</strong> <span id="detailTransmission"></span></p>
                                <p><strong>Mileage:</strong> <span id="detailMileage"></span> km</p>
                            </div>
                            <div class="col-md-6">
                                <h4>Engine Details</h4>
                                <p><strong>Engine Size:</strong> <span id="detailEngineSize"></span> cc</p>
                                <p><strong>Power:</strong> <span id="detailPower"></span> hp</p>
                            </div>
                        </div>
                    </div>

                    <!-- History Tab -->
                    <div class="tab-pane fade" id="history" role="tabpanel">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h4>Ownership History</h4>
                                <p><strong>Owner Type:</strong> <span id="detailOwnerType"></span></p>
                                <p><strong>Seller:</strong> <span id="detailSeller"></span></p>
                                <p><strong>Purchase Date:</strong> <span id="detailPurchaseDate"></span></p>
                                <p><strong>Purchase Price:</strong> <span id="detailPurchasePrice"></span></p>
                            </div>
                            <div class="col-md-6">
                                <h4>Related Records</h4>
                                <div id="relatedRecords">
                                    <!-- Related records will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicles List -->
        <div class="card">
            <div class="card-header">
                <h5>Vehicles List</h5>
            </div>
            <div class="card-body">
                <div class="loading">Loading vehicles...</div>
                <div class="error-message"></div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>VIN</th>
                                <th>Manufacturer</th>
                                <th>Model</th>
                                <th>Year</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="vehiclesTableBody">
                            <!-- Vehicles will be loaded here -->
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
            const vehiclesTableBody = document.getElementById('vehiclesTableBody');
            const vehicleForm = document.getElementById('vehicleForm');
            const vehicleDetails = document.getElementById('vehicleDetails');
            const vehicleDataForm = document.getElementById('vehicleDataForm');
            const showFormBtn = document.getElementById('showFormBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const formTitle = document.getElementById('formTitle');
            const backToListBtn = document.getElementById('backToListBtn');
            const editVehicleBtn = document.getElementById('editVehicleBtn');
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');

            // Form fields
            const vehicleIdField = document.getElementById('vehicleId');
            const vinField = document.getElementById('vin');
            const licensePlateField = document.getElementById('license_plate');
            const manufacturerField = document.getElementById('manufacturer_id');
            const modelField = document.getElementById('model_id');
            const yearField = document.getElementById('year');
            const colorField = document.getElementById('color');
            const statusField = document.getElementById('status');
            const vehicleTypeField = document.getElementById('vehicle_type_id');
            const driveTypeField = document.getElementById('drive_type_id');
            const fuelTypeField = document.getElementById('fuel_type_id');
            const transmissionField = document.getElementById('transmission_id');
            const mileageField = document.getElementById('mileage');
            const engineSizeField = document.getElementById('engine_size');
            const powerField = document.getElementById('power');
            const ownerTypeField = document.getElementById('owner_type_id');
            const sellerField = document.getElementById('seller_id');
            const purchaseDateField = document.getElementById('purchase_date');
            const purchasePriceField = document.getElementById('purchase_price');
            const descriptionField = document.getElementById('description');
            const listPriceField = document.getElementById('list_price');
            const featuresField = document.getElementById('features');
            const notesField = document.getElementById('notes');

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

            // Fetch all vehicles
            function fetchVehicles(searchTerm = '') {
                showLoading();
                hideError();

                let url = '/api/vehicles';
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
                            throw new Error('Failed to fetch vehicles');
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        renderVehicles(data);
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error loading vehicles: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Render vehicles in the table
            function renderVehicles(vehicles) {
                vehiclesTableBody.innerHTML = '';

                if (vehicles.length === 0) {
                    const row = document.createElement('tr');
                    row.innerHTML = '<td colspan="6" class="text-center">No vehicles found</td>';
                    vehiclesTableBody.appendChild(row);
                    return;
                }

                vehicles.forEach(vehicle => {
                    const row = document.createElement('tr');

                    // Determine status class
                    let statusClass = '';
                    switch (vehicle.status) {
                        case 'Available':
                            statusClass = 'status-available';
                            break;
                        case 'Sold':
                            statusClass = 'status-sold';
                            break;
                        case 'Reserved':
                            statusClass = 'status-reserved';
                            break;
                        default:
                            break;
                    }

                    // Safely access nested properties with fallbacks
                    const manufacturerName = vehicle.manufacturer ? vehicle.manufacturer.name : '-';
                    const modelName = vehicle.model ? vehicle.model.model_name : '-';

                    row.innerHTML = `
<td>${vehicle.vin}</td>
<td>${manufacturerName}</td>
<td>${modelName}</td>
<td>${vehicle.first_registration ? new Date(vehicle.first_registration).getFullYear() : '-'}</td>
<td><span class="${statusClass}">${vehicle.status}</span></td>
<td>
    <button class="btn btn-sm btn-info view-btn" data-id="${vehicle.vin}">View</button>
    <button class="btn btn-sm btn-primary edit-btn" data-id="${vehicle.vin}">Edit</button>
    <button class="btn btn-sm btn-danger delete-btn" data-id="${vehicle.vin}">Delete</button>
</td>
`;
                    vehiclesTableBody.appendChild(row);
                });

                // Add event listeners to buttons
                document.querySelectorAll('.view-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        viewVehicle(id);
                    });
                });

                document.querySelectorAll('.edit-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        editVehicle(id);
                    });
                });

                document.querySelectorAll('.delete-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        deleteVehicle(id);
                    });
                });
            }

            // Load dropdown options
            function loadDropdownOptions() {
                // Load manufacturers
                fetch('/api/manufacturers', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        manufacturerField.innerHTML = '<option value="">Select Manufacturer</option>';
                        data.forEach(item => {
                            manufacturerField.innerHTML +=
                                `<option value="${item.manufacturer_id}">${item.name}</option>`;
                        });
                    })
                    .catch(error => console.error('Error loading manufacturers:', error));

                // Load vehicle types
                fetch('/api/vehicle-types', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        vehicleTypeField.innerHTML = '<option value="">Select Vehicle Type</option>';
                        data.forEach(item => {
                            vehicleTypeField.innerHTML +=
                                `<option value="${item.type_id}">${item.type_name}</option>`;
                        });
                    })
                    .catch(error => console.error('Error loading vehicle types:', error));

                // Load drive types
                fetch('/api/drive-types', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        driveTypeField.innerHTML = '<option value="">Select Drive Type</option>';
                        data.forEach(item => {
                            driveTypeField.innerHTML +=
                                `<option value="${item.drive_type_id}">${item.drive_type_name}</option>`;
                        });
                    })
                    .catch(error => console.error('Error loading drive types:', error));

                // Load fuel types
                fetch('/api/fuel-types', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        fuelTypeField.innerHTML = '<option value="">Select Fuel Type</option>';
                        data.forEach(item => {
                            fuelTypeField.innerHTML +=
                                `<option value="${item.fuel_type_id}">${item.fuel_name}</option>`;
                        });
                    })
                    .catch(error => console.error('Error loading fuel types:', error));

                // Load transmissions
                fetch('/api/transmissions', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        transmissionField.innerHTML = '<option value="">Select Transmission</option>';
                        data.forEach(item => {
                            transmissionField.innerHTML +=
                                `<option value="${item.transmission_id}">${item.type}</option>`;
                        });
                    })
                    .catch(error => console.error('Error loading transmissions:', error));

                // Load owner types
                fetch('/api/owner-types', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        ownerTypeField.innerHTML = '<option value="">Select Owner Type</option>';
                        data.forEach(item => {
                            ownerTypeField.innerHTML +=
                                `<option value="${item.owner_type_id}">${item.type_name}</option>`;
                        });
                    })
                    .catch(error => console.error('Error loading owner types:', error));

                // Load sellers
                fetch('/api/sellers', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        sellerField.innerHTML = '<option value="">Select Seller</option>';
                        data.forEach(item => {
                            sellerField.innerHTML +=
                                `<option value="${item.seller_id}">${item.first_name} ${item.last_name}</option>`;
                        });
                    })
                    .catch(error => console.error('Error loading sellers:', error));
            }

            // Load models based on selected manufacturer
            manufacturerField.addEventListener('change', function() {
                const manufacturerId = this.value;
                if (!manufacturerId) {
                    modelField.innerHTML = '<option value="">Select Model</option>';
                    return;
                }

                fetch(`/api/manufacturers/${manufacturerId}/models`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        modelField.innerHTML = '<option value="">Select Model</option>';
                        data.forEach(item => {
                            modelField.innerHTML +=
                                `<option value="${item.model_id}">${item.model_name}</option>`;
                        });
                    })
                    .catch(error => console.error('Error loading models:', error));
            });

            // Show form for adding a new vehicle
            showFormBtn.addEventListener('click', function() {
                formTitle.textContent = 'Add New Vehicle';
                vehicleIdField.value = '';
                vehicleDataForm.reset();
                vehicleForm.style.display = 'block';
                vehicleDetails.style.display = 'none';
                showFormBtn.style.display = 'none';

                // Load dropdown options
                loadDropdownOptions();

                // Set default values
                yearField.value = new Date().getFullYear();
                statusField.value = 'available';
            });

            // Hide form
            cancelBtn.addEventListener('click', function() {
                vehicleForm.style.display = 'none';
                showFormBtn.style.display = 'block';
                hideError();
            });

            // Back to list from details view
            backToListBtn.addEventListener('click', function() {
                vehicleDetails.style.display = 'none';
                showFormBtn.style.display = 'block';
            });

            // Edit from details view
            editVehicleBtn.addEventListener('click', function() {
                const vin = document.getElementById('detailVin').textContent;
                editVehicle(vin);
            });

            // Handle form submission
            vehicleDataForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const vehicleId = vehicleIdField.value;
                const vehicleData = {
                    vin: vinField.value,
                    license_plate: licensePlateField.value,
                    manufacturer_id: manufacturerField.value,
                    model_id: modelField.value,
                    year: yearField.value,
                    color: colorField.value,
                    status: statusField.value,
                    vehicle_type_id: vehicleTypeField.value || null,
                    drive_type_id: driveTypeField.value || null,
                    fuel_type_id: fuelTypeField.value || null,
                    transmission_id: transmissionField.value || null,
                    mileage: mileageField.value || null,
                    engine_size: engineSizeField.value || null,
                    power: powerField.value || null,
                    owner_type_id: ownerTypeField.value || null,
                    seller_id: sellerField.value || null,
                    purchase_date: purchaseDateField.value || null,
                    purchase_price: purchasePriceField.value || null,
                    description: descriptionField.value || null,
                    list_price: listPriceField.value || null,
                    features: featuresField.value || null,
                    notes: notesField.value || null
                };

                if (vehicleId) {
                    updateVehicle(vehicleId, vehicleData);
                } else {
                    createVehicle(vehicleData);
                }
            });

            // Search functionality
            searchBtn.addEventListener('click', function() {
                const searchTerm = searchInput.value.trim();
                fetchVehicles(searchTerm);
            });

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const searchTerm = searchInput.value.trim();
                    fetchVehicles(searchTerm);
                }
            });

            // Create a new vehicle
            function createVehicle(vehicleData) {
                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/api/vehicles', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(vehicleData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Failed to create vehicle');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        vehicleForm.style.display = 'none';
                        showFormBtn.style.display = 'block';
                        showSuccess('Vehicle created successfully!');
                        fetchVehicles();
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error creating vehicle: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // View vehicle details
            function viewVehicle(vin) {
                showLoading();
                hideError();

                fetch(`/api/vehicles/${vin}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch vehicle details');
                        }
                        return response.json();
                    })
                    .then(vehicle => {
                        hideLoading();

                        // Display vehicle details
                        document.getElementById('detailTitle').textContent =
                            `${vehicle.first_registration ? new Date(vehicle.first_registration).getFullYear() : ''} ${vehicle.manufacturer ? vehicle.manufacturer.name : ''} ${vehicle.model ? vehicle.model.model_name : ''}`;
                        document.getElementById('detailVin').textContent = vehicle.vin;

                        // Find the line where we set the license plate value
                        // Replace this line:
                        //document.getElementById('detailLicensePlate').textContent = vehicle.license_plate || '-';

                        // With this more comprehensive code that checks the vehicle_registration relationship:
                        // For license plate, check the vehicle_registration relationship
                        let licensePlate = '-';
                        if (vehicle.vehicle_registration && vehicle.vehicle_registration.custom_license_plate) {
                            licensePlate = vehicle.vehicle_registration.custom_license_plate;
                        } else if (vehicle.vehicleRegistration && vehicle.vehicleRegistration
                            .custom_license_plate) {
                            licensePlate = vehicle.vehicleRegistration.custom_license_plate;
                        } else if (vehicle.registration && vehicle.registration.custom_license_plate) {
                            licensePlate = vehicle.registration.custom_license_plate;
                        } else if (vehicle.license_plate) {
                            licensePlate = vehicle.license_plate;
                        }
                        document.getElementById('detailLicensePlate').textContent = licensePlate;

                        // Also add some debug logging to see the registration data
                        console.log('Vehicle Registration Data:', {
                            'vehicle.vehicle_registration': vehicle.vehicle_registration,
                            'vehicle.vehicleRegistration': vehicle.vehicleRegistration,
                            'vehicle.registration': vehicle.registration
                        });

                        //document.getElementById('detailLicensePlate').textContent = vehicle.license_plate || '-';

                        // Set status with appropriate class
                        const statusElement = document.getElementById('detailStatus');
                        statusElement.textContent = vehicle.status;
                        statusElement.className = '';
                        switch (vehicle.status) {
                            case 'Available':
                                statusElement.className = 'status-available';
                                break;
                            case 'Sold':
                                statusElement.className = 'status-sold';
                                break;
                            case 'Reserved':
                                statusElement.className = 'status-reserved';
                                break;
                            default:
                                break;
                        }

                        document.getElementById('detailYear').textContent = vehicle.first_registration ?
                            new Date(vehicle.first_registration).getFullYear() : '-';
                        document.getElementById('detailColor').textContent = vehicle.color || '-';
                        document.getElementById('detailListPrice').textContent = vehicle.selling_price ?
                            `€${parseFloat(vehicle.selling_price).toFixed(2)}` : '-';
                        document.getElementById('detailDescription').textContent = vehicle.additional_info ||
                            'No description available';

                        // Format features as a list if available
                        const featuresElement = document.getElementById('detailFeatures');
                        featuresElement.textContent = 'No features listed';

                        document.getElementById('detailNotes').textContent = vehicle.additional_info || '-';

                        // Add more detailed console logging to help debug the structure
                        console.log('Full vehicle object:', JSON.stringify(vehicle, null, 2));

                        // Update the Technical specifications section with more robust property access
                        // Technical specifications
                        document.getElementById('detailVehicleType').textContent =
                            (vehicle.vehicleType && vehicle.vehicleType.type_name) ? vehicle.vehicleType
                            .type_name :
                            (vehicle.vehicle_type && vehicle.vehicle_type.type_name) ? vehicle.vehicle_type
                            .type_name :
                            (vehicle.type && vehicle.type.type_name) ? vehicle.type.type_name : '-';

                        document.getElementById('detailDriveType').textContent =
                            (vehicle.driveType && vehicle.driveType.drive_type_name) ? vehicle.driveType
                            .drive_type_name :
                            (vehicle.drive_type && vehicle.drive_type.drive_type_name) ? vehicle.drive_type
                            .drive_type_name : '-';

                        // Find the section where we're setting the technical specifications values
                        // Replace the code that sets detailFuelType, detailTransmission, and detailMileage with this more robust version:

                        // For transmission, try multiple possible property paths
                        document.getElementById('detailTransmission').textContent =
                            (vehicle.transmission && vehicle.transmission.type) ? vehicle.transmission.type :
                            (vehicle.transmission_type) ? vehicle.transmission_type :
                            (vehicle.transmission_id) ? `ID: ${vehicle.transmission_id}` : '-';

                        // For mileage, ensure we're displaying it correctly
                        document.getElementById('detailMileage').textContent =
                            vehicle.mileage ? vehicle.mileage.toString() : '-';

                        // Find the section where we're setting the engine specifications values
                        // Replace the code that sets detailEngineSize, detailPower, and detailFuelType with this more robust version:

                        // For engine specifications, try multiple possible property paths
                        const engineSpec = vehicle.engineSpecification || vehicle.engine_specification || {};

                        // For engine size (ccm), check multiple possible property paths
                        document.getElementById('detailEngineSize').textContent =
                            engineSpec.ccm ? engineSpec.ccm.toString() :
                            engineSpec.engine_size ? engineSpec.engine_size.toString() :
                            vehicle.engine_size ? vehicle.engine_size.toString() : '-';

                        // For power (hp), check multiple possible property paths
                        document.getElementById('detailPower').textContent =
                            engineSpec.hp ? engineSpec.hp.toString() :
                            engineSpec.power ? engineSpec.power.toString() :
                            vehicle.power ? vehicle.power.toString() : '-';

                        // For fuel type, we need to get the name from the fuel_types table
                        // First, log the fuel type information to see what's available
                        console.log('Fuel Type Details:', {
                            'engineSpec.fuelType': engineSpec.fuelType,
                            'engineSpec.fuel_type': engineSpec.fuel_type,
                            'vehicle.fuelType': vehicle.fuelType,
                            'vehicle.fuel_type': vehicle.fuel_type
                        });

                        // Try to get the fuel type name from the relationship
                        let fuelTypeName = '-';
                        if (engineSpec.fuelType && engineSpec.fuelType.fuel_name) {
                            fuelTypeName = engineSpec.fuelType.fuel_name;
                        } else if (engineSpec.fuel_type && engineSpec.fuel_type.fuel_name) {
                            fuelTypeName = engineSpec.fuel_type.fuel_name;
                        } else if (vehicle.fuelType && vehicle.fuelType.fuel_name) {
                            fuelTypeName = vehicle.fuelType.fuel_name;
                        } else if (vehicle.fuel_type && vehicle.fuel_type.fuel_name) {
                            fuelTypeName = vehicle.fuel_type.fuel_name;
                        }

                        // If we couldn't find the name, try to fetch it using the ID
                        if (fuelTypeName === '-') {
                            const fuelTypeId = engineSpec.fuel_type_id || vehicle.fuel_type_id;
                            if (fuelTypeId) {
                                // Make a separate fetch request to get the fuel type name
                                fetch(`/api/fuel-types/${fuelTypeId}`, {
                                        headers: {
                                            'Accept': 'application/json',
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data && data.fuel_name) {
                                            document.getElementById('detailFuelType').textContent = data
                                                .fuel_name;
                                        }
                                    })
                                    .catch(error => console.error('Error fetching fuel type:', error));

                                // Set a temporary value while we fetch the name
                                fuelTypeName = 'Loading...';
                            }
                        }

                        document.getElementById('detailFuelType').textContent = fuelTypeName;

                        // Find the section where we set the owner type value
                        // Replace this line:
                        document.getElementById('detailOwnerType').textContent = vehicle.ownerType ? vehicle
                            .ownerType.type_name : '-';

                        // With this more comprehensive code that checks multiple possible property paths:
                        // For owner type, check multiple possible property paths
                        console.log('Owner Type Details:', {
                            'vehicle.ownerType': vehicle.ownerType,
                            'vehicle.owner_type': vehicle.owner_type,
                            'vehicle.owner_type_id': vehicle.owner_type_id
                        });

                        let ownerTypeName = '-';
                        if (vehicle.ownerType && vehicle.ownerType.type_name) {
                            ownerTypeName = vehicle.ownerType.type_name;
                        } else if (vehicle.owner_type && vehicle.owner_type.type_name) {
                            ownerTypeName = vehicle.owner_type.type_name;
                        }

                        // If we couldn't find the name, try to fetch it using the ID
                        if (ownerTypeName === '-' && vehicle.owner_type_id) {
                            // Make a separate fetch request to get the owner type name
                            fetch(`/api/owner-types/${vehicle.owner_type_id}`, {
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data && data.type_name) {
                                        document.getElementById('detailOwnerType').textContent = data
                                            .type_name;
                                    }
                                })
                                .catch(error => console.error('Error fetching owner type:', error));

                            // Set a temporary value while we fetch the name
                            ownerTypeName = 'Loading...';
                        }

                        document.getElementById('detailOwnerType').textContent = ownerTypeName;
                        document.getElementById('detailSeller').textContent = vehicle.seller ?
                            `${vehicle.seller.first_name} ${vehicle.seller.last_name}` : '-';
                        document.getElementById('detailPurchaseDate').textContent = vehicle.first_registration ?
                            new Date(vehicle.first_registration).toLocaleDateString() : '-';
                        document.getElementById('detailPurchasePrice').textContent = vehicle.purchase_price ?
                            `€${parseFloat(vehicle.purchase_price).toFixed(2)}` : '-';

                        // Show related records if any
                        const relatedRecordsElement = document.getElementById('relatedRecords');
                        relatedRecordsElement.innerHTML = '';

                        if (vehicle.purchase_contracts && vehicle.purchase_contracts.length > 0) {
                            const contractsHtml = `
                            <p><strong>Purchase Contracts:</strong> ${vehicle.purchase_contracts.length}</p>
                            <ul>
                                ${vehicle.purchase_contracts.map(contract => `
                                        <li>Contract #${contract.id} - ${contract.contract_date}</li>
                                    `).join('')}
                            </ul>
                        `;
                            relatedRecordsElement.innerHTML += contractsHtml;
                        } else {
                            relatedRecordsElement.innerHTML += '<p>No related records found</p>';
                        }

                        // Show the details view
                        vehicleDetails.style.display = 'block';
                        vehicleForm.style.display = 'none';
                        showFormBtn.style.display = 'none';
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error loading vehicle details: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Fetch vehicle details for editing
            function editVehicle(vin) {
                showLoading();
                hideError();

                fetch(`/api/vehicles/${vin}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch vehicle details');
                        }
                        return response.json();
                    })
                    .then(vehicle => {
                        hideLoading();

                        // Load dropdown options
                        loadDropdownOptions();

                        // Set form title and vehicle ID
                        formTitle.textContent = `Edit Vehicle: ${vehicle.vin}`;
                        vehicleIdField.value = vehicle.vin;

                        // Populate form fields
                        vinField.value = vehicle.vin;
                        licensePlateField.value = vehicle.license_plate || '';
                        yearField.value = vehicle.year;
                        colorField.value = vehicle.color;
                        statusField.value = vehicle.status;
                        mileageField.value = vehicle.mileage || '';
                        engineSizeField.value = vehicle.engine_size || '';
                        powerField.value = vehicle.power || '';
                        purchaseDateField.value = vehicle.purchase_date || '';
                        purchasePriceField.value = vehicle.purchase_price || '';
                        descriptionField.value = vehicle.description || '';
                        listPriceField.value = vehicle.list_price || '';
                        featuresField.value = vehicle.features || '';
                        notesField.value = vehicle.notes || '';

                        // Set dropdown values (will be populated after options are loaded)
                        setTimeout(() => {
                            if (vehicle.manufacturer_id) {
                                manufacturerField.value = vehicle.manufacturer_id;

                                // Load models for this manufacturer
                                fetch(`/api/manufacturers/${vehicle.manufacturer_id}/models`, {
                                        headers: {
                                            'Accept': 'application/json',
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        modelField.innerHTML =
                                            '<option value="">Select Model</option>';
                                        data.forEach(item => {
                                            // Make sure we're using the correct property names
                                            modelField.innerHTML +=
                                                `<option value="${item.model_id}">${item.model_name}</option>`;
                                        });

                                        if (vehicle.model_id) {
                                            modelField.value = vehicle.model_id;
                                        }
                                    })
                                    .catch(error => console.error('Error loading models:', error));
                            }

                            if (vehicle.type_id) vehicleTypeField.value = vehicle.type_id;
                            if (vehicle.drive_type_id) driveTypeField.value = vehicle.drive_type_id;
                            if (vehicle.fuel_type_id) fuelTypeField.value = vehicle.fuel_type_id;
                            if (vehicle.transmission_id) transmissionField.value = vehicle
                                .transmission_id;
                            if (vehicle.owner_type_id) ownerTypeField.value = vehicle.owner_type_id;
                            if (vehicle.seller_id) sellerField.value = vehicle.seller_id;
                        }, 500);

                        // Show the form
                        vehicleForm.style.display = 'block';
                        vehicleDetails.style.display = 'none';
                        showFormBtn.style.display = 'none';
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error loading vehicle details: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Update an existing vehicle
            function updateVehicle(vin, vehicleData) {
                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/api/vehicles/${vin}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(vehicleData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Failed to update vehicle');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        vehicleForm.style.display = 'none';
                        showFormBtn.style.display = 'block';
                        showSuccess('Vehicle updated successfully!');
                        fetchVehicles();
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error updating vehicle: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Delete a vehicle
            function deleteVehicle(vin) {
                if (!confirm('Are you sure you want to delete this vehicle?')) {
                    return;
                }

                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/api/vehicles/${vin}`, {
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
                                throw new Error(data.message || 'Failed to delete vehicle');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        showSuccess('Vehicle deleted successfully!');
                        fetchVehicles();
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error deleting vehicle: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Initial load
            fetchVehicles();
        });
    </script>
</body>

</html>
