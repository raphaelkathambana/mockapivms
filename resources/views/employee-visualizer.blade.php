<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employee Visualizer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .container {
            margin-top: 20px;
        }

        .card {
            margin-bottom: 20px;
        }

        #employeeForm,
        #employeeDetails {
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
        <h1>Employee Visualizer</h1>
        <div class="row">
            <div class="col-md-6 mb-3">
                <button id="showFormBtn" class="btn btn-primary">Add New Employee</button>
                <a href="/" class="btn btn-secondary">Back to Home</a>
            </div>
            <div class="col-md-6 mb-3">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search employees...">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Employee Form -->
        <div class="card" id="employeeForm">
            <div class="card-header">
                <h5 id="formTitle">Add New Employee</h5>
            </div>
            <div class="card-body">
                <form id="employeeDataForm">
                    <input type="hidden" id="employeeId">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" required>
                                <option value="">Select Role</option>
                                <option value="Sales Manager">Sales Manager</option>
                                <option value="Sales Representative">Sales Representative</option>
                                <option value="Finance Manager">Finance Manager</option>
                                <option value="Customer Service">Customer Service</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" required>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Save Employee</button>
                        <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
                    </div>
                    <div class="loading mt-3">Saving employee data...</div>
                    <div class="error-message"></div>
                    <div class="success-message"></div>
                </form>
            </div>
        </div>

        <!-- Employee Details -->
        <div class="card" id="employeeDetails">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Employee Details</h5>
                <div>
                    <button id="editEmployeeBtn" class="btn btn-sm btn-primary">Edit</button>
                    <button id="backToListBtn" class="btn btn-sm btn-secondary">Back to List</button>
                </div>
            </div>
            <div class="card-body">
                <div class="loading">Loading employee details...</div>
                <div class="error-message"></div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <h4 id="detailName">Employee Details</h4>
                        <p><strong>Role:</strong> <span id="detailRole"></span></p>
                        <p><strong>Email:</strong> <span id="detailEmail"></span></p>
                        <p><strong>Phone Number:</strong> <span id="detailPhoneNumber"></span></p>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <h4>Sales History</h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Contract ID</th>
                                        <th>Type</th>
                                        <th>Vehicle</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody id="salesHistoryTableBody">
                                    <!-- Sales history will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employees List -->
        <div class="card">
            <div class="card-header">
                <h5>Employees List</h5>
            </div>
            <div class="card-body">
                <div class="loading">Loading employees...</div>
                <div class="error-message"></div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="employeesTableBody">
                            <!-- Employees will be loaded here -->
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
            const employeesTableBody = document.getElementById('employeesTableBody');
            const employeeForm = document.getElementById('employeeForm');
            const employeeDetails = document.getElementById('employeeDetails');
            const employeeDataForm = document.getElementById('employeeDataForm');
            const showFormBtn = document.getElementById('showFormBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const formTitle = document.getElementById('formTitle');
            const backToListBtn = document.getElementById('backToListBtn');
            const editEmployeeBtn = document.getElementById('editEmployeeBtn');
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');

            // Form fields
            const employeeIdField = document.getElementById('employeeId');
            const firstNameField = document.getElementById('first_name');
            const lastNameField = document.getElementById('last_name');
            const roleField = document.getElementById('role');
            const emailField = document.getElementById('email');
            const phoneNumberField = document.getElementById('phone_number');

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

            // Fetch all employees
            function fetchEmployees(searchTerm = '') {
                showLoading();
                hideError();

                let url = '/api/employees';
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
                            throw new Error('Failed to fetch employees');
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        renderEmployees(data);
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error loading employees: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Render employees in the table
            function renderEmployees(employees) {
                employeesTableBody.innerHTML = '';

                if (employees.length === 0) {
                    const row = document.createElement('tr');
                    row.innerHTML = '<td colspan="6" class="text-center">No employees found</td>';
                    employeesTableBody.appendChild(row);
                    return;
                }

                employees.forEach(employee => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${employee.employee_id}</td>
                        <td>${employee.first_name} ${employee.last_name}</td>
                        <td>${employee.role}</td>
                        <td>${employee.email}</td>
                        <td>${employee.phone_number}</td>
                        <td>
                            <button class="btn btn-sm btn-info view-btn" data-id="${employee.employee_id}">View</button>
                            <button class="btn btn-sm btn-primary edit-btn" data-id="${employee.employee_id}">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${employee.employee_id}">Delete</button>
                        </td>
                    `;
                    employeesTableBody.appendChild(row);
                });

                // Add event listeners to buttons
                document.querySelectorAll('.view-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        viewEmployee(id);
                    });
                });

                document.querySelectorAll('.edit-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        editEmployee(id);
                    });
                });

                document.querySelectorAll('.delete-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        deleteEmployee(id);
                    });
                });
            }

            // Show form for adding a new employee
            showFormBtn.addEventListener('click', function() {
                formTitle.textContent = 'Add New Employee';
                employeeIdField.value = '';
                employeeDataForm.reset();
                employeeForm.style.display = 'block';
                employeeDetails.style.display = 'none';
                showFormBtn.style.display = 'none';
            });

            // Hide form
            cancelBtn.addEventListener('click', function() {
                employeeForm.style.display = 'none';
                showFormBtn.style.display = 'block';
                hideError();
            });

            // Back to list from details view
            backToListBtn.addEventListener('click', function() {
                employeeDetails.style.display = 'none';
                showFormBtn.style.display = 'block';
            });

            // Edit from details view
            editEmployeeBtn.addEventListener('click', function() {
                const id = employeeIdField.value;
                editEmployee(id);
            });

            // Handle form submission
            employeeDataForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const employeeId = employeeIdField.value;
                const employeeData = {
                    first_name: firstNameField.value,
                    last_name: lastNameField.value,
                    role: roleField.value,
                    email: emailField.value,
                    phone_number: phoneNumberField.value
                };

                if (employeeId) {
                    updateEmployee(employeeId, employeeData);
                } else {
                    createEmployee(employeeData);
                }
            });

            // Search functionality
            searchBtn.addEventListener('click', function() {
                const searchTerm = searchInput.value.trim();
                fetchEmployees(searchTerm);
            });

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const searchTerm = searchInput.value.trim();
                    fetchEmployees(searchTerm);
                }
            });

            // Create a new employee
            function createEmployee(employeeData) {
                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/api/employees', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(employeeData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Failed to create employee');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        employeeForm.style.display = 'none';
                        showFormBtn.style.display = 'block';
                        showSuccess('Employee created successfully!');
                        fetchEmployees();
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error creating employee: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // View employee details
            function viewEmployee(id) {
                showLoading();
                hideError();

                fetch(`/api/employees/${id}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch employee details');
                        }
                        return response.json();
                    })
                    .then(employee => {
                        hideLoading();

                        // Store employee ID for edit button
                        employeeIdField.value = employee.employee_id;

                        // Display employee details
                        document.getElementById('detailName').textContent =
                            `${employee.first_name} ${employee.last_name}`;
                        document.getElementById('detailRole').textContent = employee.role;
                        document.getElementById('detailEmail').textContent = employee.email;
                        document.getElementById('detailPhoneNumber').textContent = employee.phone_number;

                        // Load sales history
                        const salesHistoryTableBody = document.getElementById('salesHistoryTableBody');
                        salesHistoryTableBody.innerHTML = '';

                        // Combine purchase contracts and procurement contracts for the sales history
                        const allContracts = [];

                        // Add purchase contracts
                        if (employee.purchase_contracts && employee.purchase_contracts.length > 0) {
                            employee.purchase_contracts.forEach(contract => {
                                allContracts.push({
                                    id: contract.contract_id,
                                    type: 'Purchase',
                                    vehicle: contract.vehicle,
                                    customer: contract.buyer,
                                    date: contract.contract_date
                                });
                            });
                        }

                        // Add procurement contracts
                        if (employee.procurement_contracts && employee.procurement_contracts.length > 0) {
                            employee.procurement_contracts.forEach(contract => {
                                allContracts.push({
                                    id: contract.contract_id,
                                    type: 'Procurement',
                                    vehicle: contract.vehicle,
                                    customer: contract.seller,
                                    date: contract.contract_date
                                });
                            });
                        }

                        if (allContracts.length > 0) {
                            allContracts.sort((a, b) => new Date(b.date) - new Date(a.date));

                            allContracts.forEach(contract => {
                                let vehicle = contract.vehicle || {};
                                let customer = contract.type === 'Purchase' ? contract.customer : contract.customer;
                                
                                const vehicleInfo = vehicle ? 
                                    `${vehicle.manufacturer?.name || ''} ${vehicle.model?.model_name || ''} (${vehicle.vin || 'No VIN'})` : 
                                    'N/A';
                                
                                const customerName = customer ?
                                    `${customer.first_name} ${customer.last_name}` :
                                    'N/A';

                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td>${contract.id}</td>
                                    <td>${contract.type}</td>
                                    <td>${vehicleInfo}</td>
                                    <td>${customerName}</td>
                                    <td>${new Date(contract.date).toLocaleDateString()}</td>
                                `;
                                salesHistoryTableBody.appendChild(row);
                            });
                        } else {
                            const row = document.createElement('tr');
                            row.innerHTML = '<td colspan="5" class="text-center">No sales history found</td>';
                            salesHistoryTableBody.appendChild(row);
                        }

                        // Show the details view
                        employeeDetails.style.display = 'block';
                        employeeForm.style.display = 'none';
                        showFormBtn.style.display = 'none';
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error loading employee details: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Fetch employee details for editing
            function editEmployee(id) {
                showLoading();
                hideError();

                fetch(`/api/employees/${id}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch employee details');
                        }
                        return response.json();
                    })
                    .then(employee => {
                        hideLoading();

                        // Set form title and employee ID
                        formTitle.textContent = `Edit Employee: ${employee.first_name} ${employee.last_name}`;
                        employeeIdField.value = employee.employee_id;

                        // Populate form fields
                        firstNameField.value = employee.first_name;
                        lastNameField.value = employee.last_name;
                        roleField.value = employee.role;
                        emailField.value = employee.email;
                        phoneNumberField.value = employee.phone_number;

                        // Show the form
                        employeeForm.style.display = 'block';
                        employeeDetails.style.display = 'none';
                        showFormBtn.style.display = 'none';
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error loading employee details: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Update an existing employee
            function updateEmployee(id, employeeData) {
                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/api/employees/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(employeeData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Failed to update employee');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        employeeForm.style.display = 'none';
                        showFormBtn.style.display = 'block';
                        showSuccess('Employee updated successfully!');
                        fetchEmployees();
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error updating employee: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Delete an employee
            function deleteEmployee(id) {
                if (!confirm('Are you sure you want to delete this employee? This cannot be undone.')) {
                    return;
                }

                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/api/employees/${id}`, {
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
                                throw new Error(data.message || 'Failed to delete employee');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        showSuccess('Employee deleted successfully!');
                        fetchEmployees();
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error deleting employee: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Initial load
            fetchEmployees();
        });
    </script>
</body>

</html>
