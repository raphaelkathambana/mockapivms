<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Procurement Contract Visualizer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .container {
            margin-top: 20px;
        }

        .card {
            margin-bottom: 20px;
        }

        #contractForm,
        #contractDetails {
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
    </style>
</head>

<body>
    <div class="container">
        <h1>Procurement Contract Visualizer</h1>
        <div class="row">
            <div class="col-md-6 mb-3">
                <button id="showFormBtn" class="btn btn-primary">Add New Contract</button>
                <a href="/" class="btn btn-secondary">Back to Home</a>
            </div>
            <div class="col-md-6 mb-3">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search contracts...">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Contract Form -->
        <div class="card" id="contractForm">
            <div class="card-header">
                <h5 id="formTitle">Add New Procurement Contract</h5>
            </div>
            <div class="card-body">
                <form id="contractDataForm">
                    <input type="hidden" id="contractId">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="vin" class="form-label">Vehicle (VIN)</label>
                            <select class="form-select" id="vin" required>
                                <option value="">Select Vehicle</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="seller_id" class="form-label">Seller</label>
                            <select class="form-select" id="seller_id" required>
                                <option value="">Select Seller</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="employee_id" class="form-label">Employee</label>
                            <select class="form-select" id="employee_id" required>
                                <option value="">Select Employee</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="contract_date" class="form-label">Contract Date</label>
                            <input type="date" class="form-control" id="contract_date" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="purchase_amount" class="form-label">Purchase Amount</label>
                            <input type="number" class="form-control" id="purchase_amount" required step="0.01" min="0">
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Save Contract</button>
                        <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
                    </div>
                    <div class="loading mt-3">Saving contract data...</div>
                    <div class="error-message"></div>
                    <div class="success-message"></div>
                </form>
            </div>
        </div>

        <!-- Contract Details -->
        <div class="card" id="contractDetails">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Contract Details</h5>
                <div>
                    <button id="editContractBtn" class="btn btn-sm btn-primary">Edit</button>
                    <button id="backToListBtn" class="btn btn-sm btn-secondary">Back to List</button>
                </div>
            </div>
            <div class="card-body">
                <div class="loading">Loading contract details...</div>
                <div class="error-message"></div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <h4>Contract Information</h4>
                        <p><strong>Contract ID:</strong> <span id="detailContractId"></span></p>
                        <p><strong>Contract Date:</strong> <span id="detailContractDate"></span></p>
                        <p><strong>Employee:</strong> <span id="detailEmployee"></span></p>
                        <p><strong>Purchase Amount:</strong> <span id="detailPurchaseAmount"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h4>Seller Information</h4>
                        <p><strong>Name:</strong> <span id="detailSellerName"></span></p>
                        <p><strong>Customer Number:</strong> <span id="detailSellerNumber"></span></p>
                        <p><strong>Contact:</strong> <span id="detailSellerContact"></span></p>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <h4>Vehicle Information</h4>
                        <p><strong>VIN:</strong> <span id="detailVin"></span></p>
                        <p><strong>Vehicle:</strong> <span id="detailVehicle"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contracts List -->
        <div class="card">
            <div class="card-header">
                <h5>Procurement Contracts List</h5>
            </div>
            <div class="card-body">
                <div class="loading">Loading contracts...</div>
                <div class="error-message"></div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vehicle</th>
                                <th>Seller</th>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="contractsTableBody">
                            <!-- Contracts will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/api-client.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const contractsTableBody = document.getElementById('contractsTableBody');
            const contractForm = document.getElementById('contractForm');
            const contractDetails = document.getElementById('contractDetails');
            const contractDataForm = document.getElementById('contractDataForm');
            const showFormBtn = document.getElementById('showFormBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const formTitle = document.getElementById('formTitle');
            const backToListBtn = document.getElementById('backToListBtn');
            const editContractBtn = document.getElementById('editContractBtn');
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');

            // Form fields
            const contractIdField = document.getElementById('contractId');
            const vinField = document.getElementById('vin');
            const sellerIdField = document.getElementById('seller_id');
            const employeeIdField = document.getElementById('employee_id');
            const contractDateField = document.getElementById('contract_date');
            const purchaseAmountField = document.getElementById('purchase_amount');

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

            // Fetch all contracts
            function fetchContracts(searchTerm = '') {
                showLoading();
                hideError();

                let url = '/api/procurement-contracts';
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
                            throw new Error('Failed to fetch contracts');
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        renderContracts(data);
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error loading contracts: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Render contracts in the table
            function renderContracts(contracts) {
                contractsTableBody.innerHTML = '';

                if (contracts.length === 0) {
                    const row = document.createElement('tr');
                    row.innerHTML = '<td colspan="7" class="text-center">No contracts found</td>';
                    contractsTableBody.appendChild(row);
                    return;
                }

                contracts.forEach(contract => {
                    const row = document.createElement('tr');

                    // Format date and amount
                    const contractDate = contract.contract_date ? new Date(contract.contract_date)
                        .toLocaleDateString() : '-';
                    const purchaseAmount = contract.purchase_amount ?
                        `€${parseFloat(contract.purchase_amount).toFixed(2)}` : '-';

                    // Get vehicle info
                    const vehicleInfo = contract.vehicle ? contract.vehicle.vin : '-';

                    // Get seller info
                    const sellerInfo = contract.seller ?
                        `${contract.seller.first_name} ${contract.seller.last_name}` : '-';

                    // Get employee info
                    const employeeInfo = contract.employee ?
                        `${contract.employee.first_name} ${contract.employee.last_name}` : '-';

                    row.innerHTML = `
                    <td>${contract.contract_id}</td>
                    <td>${vehicleInfo}</td>
                    <td>${sellerInfo}</td>
                    <td>${employeeInfo}</td>
                    <td>${contractDate}</td>
                    <td>${purchaseAmount}</td>
                    <td>
                        <button class="btn btn-sm btn-info view-btn" data-id="${contract.contract_id}">View</button>
                        <button class="btn btn-sm btn-primary edit-btn" data-id="${contract.contract_id}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${contract.contract_id}">Delete</button>
                    </td>
                `;
                    contractsTableBody.appendChild(row);
                });

                // Add event listeners to buttons
                document.querySelectorAll('.view-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        viewContract(id);
                    });
                });

                document.querySelectorAll('.edit-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        editContract(id);
                    });
                });

                document.querySelectorAll('.delete-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        deleteContract(id);
                    });
                });
            }

            // Load dropdown options
            function loadDropdownOptions() {
                // Load vehicles
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
                            const manufacturerName = vehicle.manufacturer ? vehicle.manufacturer.name :
                                '';
                            const modelName = vehicle.model ? vehicle.model.model_name : '';
                            vinField.innerHTML +=
                                `<option value="${vehicle.vin}">${vehicle.vin} - ${manufacturerName} ${modelName}</option>`;
                        });
                    })
                    .catch(error => console.error('Error loading vehicles:', error));

                // Load sellers
                fetch('/api/sellers', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        sellerIdField.innerHTML = '<option value="">Select Seller</option>';
                        data.forEach(seller => {
                            sellerIdField.innerHTML +=
                                `<option value="${seller.seller_id}">${seller.first_name} ${seller.last_name}</option>`;
                        });
                    })
                    .catch(error => console.error('Error loading sellers:', error));

                // Load employees
                fetch('/api/employees', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        employeeIdField.innerHTML = '<option value="">Select Employee</option>';
                        data.forEach(employee => {
                            employeeIdField.innerHTML +=
                                `<option value="${employee.employee_id}">${employee.first_name} ${employee.last_name} (${employee.role})</option>`;
                        });
                    })
                    .catch(error => console.error('Error loading employees:', error));
            }

            // Show form for adding a new contract
            showFormBtn.addEventListener('click', function() {
                formTitle.textContent = 'Add New Procurement Contract';
                contractIdField.value = '';
                contractDataForm.reset();
                contractForm.style.display = 'block';
                contractDetails.style.display = 'none';
                showFormBtn.style.display = 'none';

                // Load dropdown options
                loadDropdownOptions();

                // Set default contract date to today
                contractDateField.value = new Date().toISOString().split('T')[0];
            });

            // Hide form
            cancelBtn.addEventListener('click', function() {
                contractForm.style.display = 'none';
                showFormBtn.style.display = 'block';
                hideError();
            });

            // Back to list from details view
            backToListBtn.addEventListener('click', function() {
                contractDetails.style.display = 'none';
                showFormBtn.style.display = 'block';
            });

            // Edit from details view
            editContractBtn.addEventListener('click', function() {
                const id = contractIdField.value;
                editContract(id);
            });

            // Handle form submission
            contractDataForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const contractId = contractIdField.value;
                const contractData = {
                    vin: vinField.value,
                    seller_id: sellerIdField.value,
                    employee_id: employeeIdField.value,
                    contract_date: contractDateField.value,
                    purchase_amount: purchaseAmountField.value
                };

                if (contractId) {
                    updateContract(contractId, contractData);
                } else {
                    createContract(contractData);
                }
            });

            // Search functionality
            searchBtn.addEventListener('click', function() {
                const searchTerm = searchInput.value.trim();
                fetchContracts(searchTerm);
            });

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const searchTerm = searchInput.value.trim();
                    fetchContracts(searchTerm);
                }
            });

            // Create a new contract
            function createContract(contractData) {
                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/api/procurement-contracts', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(contractData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Failed to create contract');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        contractForm.style.display = 'none';
                        showFormBtn.style.display = 'block';
                        showSuccess('Contract created successfully!');
                        fetchContracts();
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error creating contract: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // View contract details
            function viewContract(id) {
                showLoading();
                hideError();

                fetch(`/api/procurement-contracts/${id}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch contract details');
                        }
                        return response.json();
                    })
                    .then(contract => {
                        hideLoading();

                        // Store contract ID for edit button
                        contractIdField.value = contract.contract_id;

                        // Display contract details
                        document.getElementById('detailContractId').textContent = contract.contract_id;
                        document.getElementById('detailContractDate').textContent = contract.contract_date ?
                            new Date(contract.contract_date).toLocaleDateString() : '-';
                        document.getElementById('detailPurchaseAmount').textContent = contract.purchase_amount ?
                            `€${parseFloat(contract.purchase_amount).toFixed(2)}` : '-';

                        // Employee info
                        if (contract.employee) {
                            document.getElementById('detailEmployee').textContent =
                                `${contract.employee.first_name} ${contract.employee.last_name} (${contract.employee.role})`;
                        } else {
                            document.getElementById('detailEmployee').textContent = '-';
                        }

                        // Seller info
                        if (contract.seller) {
                            document.getElementById('detailSellerName').textContent =
                                `${contract.seller.first_name} ${contract.seller.last_name}`;
                            document.getElementById('detailSellerContact').textContent =
                                `${contract.seller.email} / ${contract.seller.phone_number}`;
                        } else {
                            document.getElementById('detailSellerName').textContent = '-';
                            document.getElementById('detailSellerContact').textContent = '-';
                        }

                        // Vehicle info
                        if (contract.vehicle) {
                            document.getElementById('detailVin').textContent = contract.vehicle.vin;

                            let manufacturerName = contract.vehicle.manufacturer ? contract.vehicle.manufacturer
                                .name : '-';
                            let modelName = contract.vehicle.model ? contract.vehicle.model.model_name : '-';
                            let year = contract.vehicle.first_registration ? new Date(contract.vehicle
                                .first_registration).getFullYear() : '';

                            if (manufacturerName === '-' || modelName === '-') {
                                document.getElementById('detailVehicle').textContent =
                                    'Loading vehicle details...';

                                fetch(`/api/vehicles/${contract.vehicle.vin}`, {
                                        headers: {
                                            'Accept': 'application/json',
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error(
                                                `Failed to fetch vehicle details for ${contract.vehicle.vin}`
                                            );
                                        }
                                        return response.json();
                                    })
                                    .then(vehicleData => {
                                        manufacturerName = vehicleData.manufacturer ? vehicleData
                                            .manufacturer.name : '-';
                                        modelName = vehicleData.model ? vehicleData.model.model_name : '-';
                                        year = vehicleData.first_registration ? new Date(vehicleData
                                            .first_registration).getFullYear() : '';

                                        document.getElementById('detailVehicle').textContent =
                                            `${year} ${manufacturerName} ${modelName}`;
                                    })
                                    .catch(error => {
                                        console.error('Error fetching vehicle details:', error);
                                        document.getElementById('detailVehicle').textContent =
                                            'Error loading vehicle details';
                                    });
                            } else {
                                document.getElementById('detailVehicle').textContent =
                                    `${year} ${manufacturerName} ${modelName}`;
                            }
                        } else {
                            document.getElementById('detailVin').textContent = '-';
                            document.getElementById('detailVehicle').textContent = '-';
                        }

                        // Show the details view
                        contractDetails.style.display = 'block';
                        contractForm.style.display = 'none';
                        showFormBtn.style.display = 'none';
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error loading contract details: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Fetch contract details for editing
            function editContract(id) {
                showLoading();
                hideError();

                fetch(`/api/procurement-contracts/${id}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch contract details');
                        }
                        return response.json();
                    })
                    .then(contract => {
                        hideLoading();

                        // Load dropdown options
                        loadDropdownOptions();

                        // Set form title and contract ID
                        formTitle.textContent = `Edit Procurement Contract #${contract.contract_id}`;
                        contractIdField.value = contract.contract_id;

                        // Set contract date and purchase amount
                        if (contract.contract_date) {
                            contractDateField.value = contract.contract_date.split('T')[0];
                        }
                        if (contract.purchase_amount) {
                            purchaseAmountField.value = contract.purchase_amount;
                        }

                        // Set dropdown values after a short delay to ensure they're loaded
                        setTimeout(() => {
                            if (contract.vin) {
                                vinField.value = contract.vin;
                            }
                            if (contract.seller_id) {
                                sellerIdField.value = contract.seller_id;
                            }
                            if (contract.employee_id) {
                                employeeIdField.value = contract.employee_id;
                            }
                        }, 500);

                        // Show the form
                        contractForm.style.display = 'block';
                        contractDetails.style.display = 'none';
                        showFormBtn.style.display = 'none';
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error loading contract details: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Update an existing contract
            function updateContract(id, contractData) {
                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/api/procurement-contracts/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(contractData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Failed to update contract');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        contractForm.style.display = 'none';
                        showFormBtn.style.display = 'block';
                        showSuccess('Contract updated successfully!');
                        fetchContracts();
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error updating contract: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Delete a contract
            function deleteContract(id) {
                if (!confirm('Are you sure you want to delete this procurement contract?')) {
                    return;
                }

                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/api/procurement-contracts/${id}`, {
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
                                throw new Error(data.message || 'Failed to delete contract');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        showSuccess('Contract deleted successfully!');
                        fetchContracts();
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error deleting contract: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Initial load
            fetchContracts();
        });
    </script>
</body>

</html>
