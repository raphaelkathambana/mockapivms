<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Additional Equipment Visualizer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .container { margin-top: 20px; }
        .card { margin-bottom: 20px; }
        #equipmentForm, #equipmentDetails { display: none; }
        .loading { display: none; }
        .error-message { color: red; margin-top: 10px; }
        .success-message { color: green; margin-top: 10px; }
        .tab-content { padding: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Additional Equipment Visualizer</h1>
        <div class="row">
            <div class="col-md-6 mb-3">
                <button id="showFormBtn" class="btn btn-primary">Add New Equipment</button>
                <a href="/" class="btn btn-secondary">Back to Home</a>
            </div>
            <div class="col-md-6 mb-3">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search equipment...">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Equipment Form -->
        <div class="card" id="equipmentForm">
            <div class="card-header">
                <h5 id="formTitle">Add New Equipment</h5>
            </div>
            <div class="card-body">
                <form id="equipmentDataForm">
                    <input type="hidden" id="equipmentId">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="vin" class="form-label">Vehicle (VIN)</label>
                            <select class="form-select" id="vin" required>
                                <option value="">Select Vehicle</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="equipment_description" class="form-label">Equipment Description</label>
                            <textarea class="form-control" id="equipment_description" rows="3" required></textarea>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Save Equipment</button>
                        <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
                    </div>
                    <div class="loading mt-3">Saving equipment data...</div>
                    <div class="error-message"></div>
                    <div class="success-message"></div>
                </form>
            </div>
        </div>

        <!-- Equipment Details -->
        <div class="card" id="equipmentDetails">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Equipment Details</h5>
                <div>
                    <button id="editEquipmentBtn" class="btn btn-sm btn-primary">Edit</button>
                    <button id="backToListBtn" class="btn btn-sm btn-secondary">Back to List</button>
                </div>
            </div>
            <div class="card-body">
                <div class="loading">Loading equipment details...</div>
                <div class="error-message"></div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <h4>Equipment Description</h4>
                        <p id="detailDescription"></p>
                    </div>
                    <div class="col-md-6">
                        <h4>Vehicle Information</h4>
                        <p><strong>VIN:</strong> <span id="detailVin"></span></p>
                        <p><strong>Vehicle:</strong> <span id="detailVehicle"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Equipment List -->
        <div class="card">
            <div class="card-header">
                <h5>Additional Equipment List</h5>
            </div>
            <div class="card-body">
                <div class="loading">Loading equipment...</div>
                <div class="error-message"></div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vehicle</th>
                                <th>Equipment Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="equipmentTableBody">
                            <!-- Equipment will be loaded here -->
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
        const equipmentTableBody = document.getElementById('equipmentTableBody');
        const equipmentForm = document.getElementById('equipmentForm');
        const equipmentDetails = document.getElementById('equipmentDetails');
        const equipmentDataForm = document.getElementById('equipmentDataForm');
        const showFormBtn = document.getElementById('showFormBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const formTitle = document.getElementById('formTitle');
        const backToListBtn = document.getElementById('backToListBtn');
        const editEquipmentBtn = document.getElementById('editEquipmentBtn');
        const searchInput = document.getElementById('searchInput');
        const searchBtn = document.getElementById('searchBtn');

        // Form fields
        const equipmentIdField = document.getElementById('equipmentId');
        const vinField = document.getElementById('vin');
        const equipmentDescriptionField = document.getElementById('equipment_description');

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

        // Fetch all equipment
        function fetchEquipment(searchTerm = '') {
            showLoading();
            hideError();

            let url = '/api/additional-equipment';
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
                    throw new Error('Failed to fetch equipment');
                }
                return response.json();
            })
            .then(data => {
                hideLoading();
                renderEquipment(data);
            })
            .catch(error => {
                hideLoading();
                showError('Error loading equipment: ' + error.message);
                console.error('Error:', error);
            });
        }

        // Render equipment in the table
        function renderEquipment(equipmentList) {
            equipmentTableBody.innerHTML = '';

            if (equipmentList.length === 0) {
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="4" class="text-center">No equipment found</td>';
                equipmentTableBody.appendChild(row);
                return;
            }

            equipmentList.forEach(equipment => {
                const row = document.createElement('tr');

                // Get vehicle info
                const vehicleInfo = equipment.vehicle ? equipment.vehicle.vin : '-';

                row.innerHTML = `
                    <td>${equipment.equipment_id}</td>
                    <td>${vehicleInfo}</td>
                    <td>${equipment.equipment_description}</td>
                    <td>
                        <button class="btn btn-sm btn-info view-btn" data-id="${equipment.equipment_id}">View</button>
                        <button class="btn btn-sm btn-primary edit-btn" data-id="${equipment.equipment_id}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${equipment.equipment_id}">Delete</button>
                    </td>
                `;
                equipmentTableBody.appendChild(row);
            });

            // Add event listeners to buttons
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    viewEquipment(id);
                });
            });

            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    editEquipment(id);
                });
            });

            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    deleteEquipment(id);
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
                    const manufacturerName = vehicle.manufacturer ? vehicle.manufacturer.name : '';
                    const modelName = vehicle.model ? vehicle.model.model_name : '';
                    vinField.innerHTML += `<option value="${vehicle.vin}">${vehicle.vin} - ${manufacturerName} ${modelName}</option>`;
                });
            })
            .catch(error => console.error('Error loading vehicles:', error));
        }

        // Show form for adding new equipment
        showFormBtn.addEventListener('click', function() {
            formTitle.textContent = 'Add New Equipment';
            equipmentIdField.value = '';
            equipmentDataForm.reset();
            equipmentForm.style.display = 'block';
            equipmentDetails.style.display = 'none';
            showFormBtn.style.display = 'none';

            // Load vehicles for dropdown
            loadVehicles();
        });

        // Hide form
        cancelBtn.addEventListener('click', function() {
            equipmentForm.style.display = 'none';
            showFormBtn.style.display = 'block';
            hideError();
        });

        // Back to list from details view
        backToListBtn.addEventListener('click', function() {
            equipmentDetails.style.display = 'none';
            showFormBtn.style.display = 'block';
        });

        // Edit from details view
        editEquipmentBtn.addEventListener('click', function() {
            const id = equipmentIdField.value;
            editEquipment(id);
        });

        // Handle form submission
        equipmentDataForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const equipmentId = equipmentIdField.value;
            const equipmentData = {
                vin: vinField.value,
                equipment_description: equipmentDescriptionField.value
            };

            if (equipmentId) {
                updateEquipment(equipmentId, equipmentData);
            } else {
                createEquipment(equipmentData);
            }
        });

        // Search functionality
        searchBtn.addEventListener('click', function() {
            const searchTerm = searchInput.value.trim();
            fetchEquipment(searchTerm);
        });

        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = searchInput.value.trim();
                fetchEquipment(searchTerm);
            }
        });

        // Create new equipment
        function createEquipment(equipmentData) {
            showLoading();
            hideError();

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/api/additional-equipment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(equipmentData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Failed to create equipment');
                    });
                }
                return response.json();
            })
            .then(data => {
                hideLoading();
                equipmentForm.style.display = 'none';
                showFormBtn.style.display = 'block';
                showSuccess('Equipment created successfully!');
                fetchEquipment();
            })
            .catch(error => {
                hideLoading();
                showError('Error creating equipment: ' + error.message);
                console.error('Error:', error);
            });
        }

        // View equipment details
        function viewEquipment(id) {
            showLoading();
            hideError();

            fetch(`/api/additional-equipment/${id}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch equipment details');
                }
                return response.json();
            })
            .then(equipment => {
                hideLoading();

                console.log('Equipment details:', equipment);

                // Store equipment ID for edit button
                equipmentIdField.value = equipment.equipment_id;

                // Display equipment details
                document.getElementById('detailDescription').textContent = equipment.equipment_description;
                document.getElementById('detailVin').textContent = equipment.vin;

                // Fetch vehicle details to display manufacturer and model
                fetch(`/api/vehicles/${equipment.vin}`, {
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
                equipmentDetails.style.display = 'block';
                equipmentForm.style.display = 'none';
                showFormBtn.style.display = 'none';
            })
            .catch(error => {
                hideLoading();
                showError('Error loading equipment details: ' + error.message);
                console.error('Error:', error);
            });
        }

        // Fetch equipment details for editing
        function editEquipment(id) {
            showLoading();
            hideError();

            fetch(`/api/additional-equipment/${id}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch equipment details');
                }
                return response.json();
            })
            .then(equipment => {
                hideLoading();

                // Load vehicles dropdown
                loadVehicles();

                // Set form title and equipment ID
                formTitle.textContent = `Edit Equipment #${equipment.equipment_id}`;
                equipmentIdField.value = equipment.equipment_id;

                // Populate form fields
                equipmentDescriptionField.value = equipment.equipment_description;

                // Set vehicle dropdown after a short delay to ensure it's loaded
                setTimeout(() => {
                    if (equipment.vin) {
                        vinField.value = equipment.vin;
                    }
                }, 500);

                // Show the form
                equipmentForm.style.display = 'block';
                equipmentDetails.style.display = 'none';
                showFormBtn.style.display = 'none';
            })
            .catch(error => {
                hideLoading();
                showError('Error loading equipment details: ' + error.message);
                console.error('Error:', error);
            });
        }

        // Update existing equipment
        function updateEquipment(id, equipmentData) {
            showLoading();
            hideError();

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/api/additional-equipment/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(equipmentData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Failed to update equipment');
                    });
                }
                return response.json();
            })
            .then(data => {
                hideLoading();
                equipmentForm.style.display = 'none';
                showFormBtn.style.display = 'block';
                showSuccess('Equipment updated successfully!');
                fetchEquipment();
            })
            .catch(error => {
                hideLoading();
                showError('Error updating equipment: ' + error.message);
                console.error('Error:', error);
            });
        }

        // Delete equipment
        function deleteEquipment(id) {
            if (!confirm('Are you sure you want to delete this equipment?')) {
                return;
            }

            showLoading();
            hideError();

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/api/additional-equipment/${id}`, {
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
                        throw new Error(data.message || 'Failed to delete equipment');
                    });
                }
                return response.json();
            })
            .then(data => {
                hideLoading();
                showSuccess('Equipment deleted successfully!');
                fetchEquipment();
            })
            .catch(error => {
                hideLoading();
                showError('Error deleting equipment: ' + error.message);
                console.error('Error:', error);
            });
        }

        // Initial load
        fetchEquipment();
    });
</script>
</body>
</html>
