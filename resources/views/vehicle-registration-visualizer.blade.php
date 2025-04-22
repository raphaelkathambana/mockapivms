<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vehicle Registration Visualizer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .container { margin-top: 20px; }
        .card { margin-bottom: 20px; }
        #registrationForm, #registrationDetails { display: none; }
        .loading { display: none; }
        .error-message { color: red; margin-top: 10px; }
        .success-message { color: green; margin-top: 10px; }
        .tab-content { padding: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Vehicle Registration Visualizer</h1>
        <div class="row">
            <div class="col-md-6 mb-3">
                <button id="showFormBtn" class="btn btn-primary">Add New Registration</button>
                <a href="/" class="btn btn-secondary">Back to Home</a>
            </div>
            <div class="col-md-6 mb-3">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search registrations...">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="card" id="registrationForm">
            <div class="card-header">
                <h5 id="formTitle">Add New Vehicle Registration</h5>
            </div>
            <div class="card-body">
                <form id="registrationDataForm">
                    <input type="hidden" id="registrationId">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="vin" class="form-label">Vehicle (VIN)</label>
                            <select class="form-select" id="vin" required>
                                <option value="">Select Vehicle</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sepa_data" class="form-label">SEPA Data</label>
                            <input type="text" class="form-control" id="sepa_data" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="custom_license_plate" class="form-label">Custom License Plate</label>
                            <input type="text" class="form-control" id="custom_license_plate">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="delivery_date" class="form-label">Delivery Date</label>
                            <input type="date" class="form-control" id="delivery_date">
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Save Registration</button>
                        <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
                    </div>
                    <div class="loading mt-3">Saving registration data...</div>
                    <div class="error-message"></div>
                    <div class="success-message"></div>
                </form>
            </div>
        </div>

        <!-- Registration Details -->
        <div class="card" id="registrationDetails">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Registration Details</h5>
                <div>
                    <button id="editRegistrationBtn" class="btn btn-sm btn-primary">Edit</button>
                    <button id="backToListBtn" class="btn btn-sm btn-secondary">Back to List</button>
                </div>
            </div>
            <div class="card-body">
                <div class="loading">Loading registration details...</div>
                <div class="error-message"></div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <h4>Registration Information</h4>
                        <p><strong>Registration ID:</strong> <span id="detailRegistrationId"></span></p>
                        <p><strong>SEPA Data:</strong> <span id="detailSepaData"></span></p>
                        <p><strong>Custom License Plate:</strong> <span id="detailCustomLicensePlate"></span></p>
                        <p><strong>Delivery Date:</strong> <span id="detailDeliveryDate"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h4>Vehicle Information</h4>
                        <p><strong>VIN:</strong> <span id="detailVin"></span></p>
                        <p><strong>Vehicle:</strong> <span id="detailVehicle"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registrations List -->
        <div class="card">
            <div class="card-header">
                <h5>Vehicle Registrations List</h5>
            </div>
            <div class="card-body">
                <div class="loading">Loading registrations...</div>
                <div class="error-message"></div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>VIN</th>
                                <th>License Plate</th>
                                <th>Delivery Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="registrationsTableBody">
                            <!-- Registrations will be loaded here -->
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
            const registrationsTableBody = document.getElementById('registrationsTableBody');
            const registrationForm = document.getElementById('registrationForm');
            const registrationDetails = document.getElementById('registrationDetails');
            const registrationDataForm = document.getElementById('registrationDataForm');
            const showFormBtn = document.getElementById('showFormBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const formTitle = document.getElementById('formTitle');
            const backToListBtn = document.getElementById('backToListBtn');
            const editRegistrationBtn = document.getElementById('editRegistrationBtn');
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');

            // Form fields
            const registrationIdField = document.getElementById('registrationId');
            const vinField = document.getElementById('vin');
            const sepaDataField = document.getElementById('sepa_data');
            const customLicensePlateField = document.getElementById('custom_license_plate');
            const deliveryDateField = document.getElementById('delivery_date');

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

            // Fetch all registrations
            function fetchRegistrations(searchTerm = '') {
                showLoading();
                hideError();

                let url = '/api/vehicle-registrations';
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
                        throw new Error('Failed to fetch registrations');
                    }
                    return response.json();
                })
                .then(data => {
                    hideLoading();
                    renderRegistrations(data);
                })
                .catch(error => {
                    hideLoading();
                    showError('Error loading registrations: ' + error.message);
                    console.error('Error:', error);
                });
            }

            // Render registrations in the table
            function renderRegistrations(registrations) {
                registrationsTableBody.innerHTML = '';

                if (registrations.length === 0) {
                    const row = document.createElement('tr');
                    row.innerHTML = '<td colspan="5" class="text-center">No registrations found</td>';
                    registrationsTableBody.appendChild(row);
                    return;
                }

                registrations.forEach(registration => {
                    const row = document.createElement('tr');

                    // Format date
                    const deliveryDate = registration.delivery_date ? new Date(registration.delivery_date).toLocaleDateString() : '-';

                    row.innerHTML = `
                        <td>${registration.registration_id}</td>
                        <td>${registration.vin}</td>
                        <td>${registration.custom_license_plate || '-'}</td>
                        <td>${deliveryDate}</td>
                        <td>
                            <button class="btn btn-sm btn-info view-btn" data-id="${registration.registration_id}">View</button>
                            <button class="btn btn-sm btn-primary edit-btn" data-id="${registration.registration_id}">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${registration.registration_id}">Delete</button>
                        </td>
                    `;
                    registrationsTableBody.appendChild(row);
                });

                // Add event listeners to buttons
                document.querySelectorAll('.view-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        viewRegistration(id);
                    });
                });

                document.querySelectorAll('.edit-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        editRegistration(id);
                    });
                });

                document.querySelectorAll('.delete-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        deleteRegistration(id);
                    });
                });
            }

            // Load vehicles for dropdown
            function loadVehicles() {
                fetch('/api/vehicles', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    vinField.innerHTML = '<option value="">Select Vehicle</option>';
                    data.forEach(vehicle => {
                        // Check if vehicle already has a registration
                        const hasRegistration = vehicle.vehicle_registration || vehicle.vehicleRegistration;
                        if (!hasRegistration) {
                            const manufacturerName = vehicle.manufacturer ? vehicle.manufacturer.name : '';
                            const modelName = vehicle.model ? vehicle.model.model_name : '';
                            vinField.innerHTML += `<option value="${vehicle.vin}">${vehicle.vin} - ${manufacturerName} ${modelName}</option>`;
                        }
                    });
                })
                .catch(error => console.error('Error loading vehicles:', error));
            }

            // Show form for adding a new registration
            showFormBtn.addEventListener('click', function() {
                formTitle.textContent = 'Add New Vehicle Registration';
                registrationIdField.value = '';
                registrationDataForm.reset();
                registrationForm.style.display = 'block';
                registrationDetails.style.display = 'none';
                showFormBtn.style.display = 'none';

                // Load vehicles for dropdown
                loadVehicles();

                // Set default delivery date to today
                deliveryDateField.value = new Date().toISOString().split('T')[0];

                // Generate a random SEPA data
                sepaDataField.value = 'SEPA-' + Math.floor(100000 + Math.random() * 900000);
            });

            // Hide form
            cancelBtn.addEventListener('click', function() {
                registrationForm.style.display = 'none';
                showFormBtn.style.display = 'block';
                hideError();
            });

            // Back to list from details view
            backToListBtn.addEventListener('click', function() {
                registrationDetails.style.display = 'none';
                showFormBtn.style.display = 'block';
            });

            // Edit from details view
            editRegistrationBtn.addEventListener('click', function() {
                const id = registrationIdField.value;
                editRegistration(id);
            });

            // Handle form submission
            registrationDataForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const registrationId = registrationIdField.value;
                const registrationData = {
                    vin: vinField.value,
                    sepa_data: sepaDataField.value,
                    custom_license_plate: customLicensePlateField.value || null,
                    delivery_date: deliveryDateField.value || null
                };

                if (registrationId) {
                    updateRegistration(registrationId, registrationData);
                } else {
                    createRegistration(registrationData);
                }
            });

            // Search functionality
            searchBtn.addEventListener('click', function() {
                const searchTerm = searchInput.value.trim();
                fetchRegistrations(searchTerm);
            });

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const searchTerm = searchInput.value.trim();
                    fetchRegistrations(searchTerm);
                }
            });

            // Create a new registration
            function createRegistration(registrationData) {
                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/api/vehicle-registrations', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(registrationData)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Failed to create registration');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    hideLoading();
                    registrationForm.style.display = 'none';
                    showFormBtn.style.display = 'block';
                    showSuccess('Registration created successfully!');
                    fetchRegistrations();
                })
                .catch(error => {
                    hideLoading();
                    showError('Error creating registration: ' + error.message);
                    console.error('Error:', error);
                });
            }

            // View registration details
            function viewRegistration(id) {
                showLoading();
                hideError();

                fetch(`/api/vehicle-registrations/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch registration details');
                    }
                    return response.json();
                })
                .then(registration => {
                    hideLoading();

                    console.log('Registration details:', registration);

                    // Store registration ID for edit button
                    registrationIdField.value = registration.registration_id;

                    // Display registration details
                    document.getElementById('detailRegistrationId').textContent = registration.registration_id;
                    document.getElementById('detailSepaData').textContent = registration.sepa_data;
                    document.getElementById('detailCustomLicensePlate').textContent = registration.custom_license_plate || 'None';
                    document.getElementById('detailDeliveryDate').textContent = registration.delivery_date ? new Date(registration.delivery_date).toLocaleDateString() : 'Not set';
                    document.getElementById('detailVin').textContent = registration.vin;

                    // Fetch vehicle details to display manufacturer and model
                    fetch(`/api/vehicles/${registration.vin}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(vehicle => {
                        const manufacturerName = vehicle.manufacturer ? vehicle.manufacturer.name : '';
                        const modelName = vehicle.model ? vehicle.model.model_name : '';
                        const year = vehicle.first_registration ? new Date(vehicle.first_registration).getFullYear() : '';

                        document.getElementById('detailVehicle').textContent = `${year} ${manufacturerName} ${modelName}`;
                    })
                    .catch(error => {
                        console.error('Error fetching vehicle details:', error);
                        document.getElementById('detailVehicle').textContent = 'Error loading vehicle details';
                    });

                    // Show the details view
                    registrationDetails.style.display = 'block';
                    registrationForm.style.display = 'none';
                    showFormBtn.style.display = 'none';
                })
                .catch(error => {
                    hideLoading();
                    showError('Error loading registration details: ' + error.message);
                    console.error('Error:', error);
                });
            }

            // Fetch registration details for editing
            function editRegistration(id) {
                showLoading();
                hideError();

                fetch(`/api/vehicle-registrations/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch registration details');
                    }
                    return response.json();
                })
                .then(registration => {
                    hideLoading();

                    // Set form title and registration ID
                    formTitle.textContent = `Edit Vehicle Registration #${registration.registration_id}`;
                    registrationIdField.value = registration.registration_id;

                    // Populate form fields
                    sepaDataField.value = registration.sepa_data;
                    customLicensePlateField.value = registration.custom_license_plate || '';

                    // Set delivery date if available
                    if (registration.delivery_date) {
                        deliveryDateField.value = registration.delivery_date.split('T')[0];
                    }

                    // Load all vehicles for dropdown
                    fetch('/api/vehicles', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        vinField.innerHTML = '<option value="">Select Vehicle</option>';
                        data.forEach(vehicle => {
                            // Include the current vehicle and vehicles without registration
                            const hasRegistration = vehicle.vehicle_registration || vehicle.vehicleRegistration;
                            if (!hasRegistration || vehicle.vin === registration.vin) {
                                const manufacturerName = vehicle.manufacturer ? vehicle.manufacturer.name : '';
                                const modelName = vehicle.model ? vehicle.model.model_name : '';
                                vinField.innerHTML += `<option value="${vehicle.vin}">${vehicle.vin} - ${manufacturerName} ${modelName}</option>`;
                            }
                        });

                        // Set the current vehicle
                        vinField.value = registration.vin;
                    })
                    .catch(error => console.error('Error loading vehicles:', error));

                    // Show the form
                    registrationForm.style.display = 'block';
                    registrationDetails.style.display = 'none';
                    showFormBtn.style.display = 'none';
                })
                .catch(error => {
                    hideLoading();
                    showError('Error loading registration details: ' + error.message);
                    console.error('Error:', error);
                });
            }

            // Update an existing registration
            function updateRegistration(id, registrationData) {
                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/api/vehicle-registrations/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(registrationData)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Failed to update registration');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    hideLoading();
                    registrationForm.style.display = 'none';
                    showFormBtn.style.display = 'block';
                    showSuccess('Registration updated successfully!');
                    fetchRegistrations();
                })
                .catch(error => {
                    hideLoading();
                    showError('Error updating registration: ' + error.message);
                    console.error('Error:', error);
                });
            }

            // Delete a registration
            function deleteRegistration(id) {
                if (!confirm('Are you sure you want to delete this registration?')) {
                    return;
                }

                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/api/vehicle-registrations/${id}`, {
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
                            throw new Error(data.message || 'Failed to delete registration');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    hideLoading();
                    showSuccess('Registration deleted successfully!');
                    fetchRegistrations();
                })
                .catch(error => {
                    hideLoading();
                    showError('Error deleting registration: ' + error.message);
                    console.error('Error:', error);
                });
            }

            // Initial load
            fetchRegistrations();
        });
    </script>
    <script src="/js/api-client.js"></script>
</body>
</html>
