<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Buyer Visualizer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .container {
            margin-top: 20px;
        }

        .card {
            margin-bottom: 20px;
        }

        #buyerForm,
        #buyerDetails {
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
        <h1>Buyer Visualizer</h1>
        <div class="row">
            <div class="col-md-6 mb-3">
                <button id="showFormBtn" class="btn btn-primary">Add New Buyer</button>
                <a href="/" class="btn btn-secondary">Back to Home</a>
            </div>
            <div class="col-md-6 mb-3">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search buyers...">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Buyer Form -->
        <div class="card" id="buyerForm">
            <div class="card-header">
                <h5 id="formTitle">Add New Buyer</h5>
            </div>
            <div class="card-body">
                <form id="buyerDataForm">
                    <input type="hidden" id="buyerId">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_number" class="form-label">Customer Number</label>
                            <input type="text" class="form-control" id="customer_number" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="address_id" class="form-label">Address</label>
                            <select class="form-select" id="address_id" required>
                                <option value="">Select Address</option>
                            </select>
                        </div>
                    </div>

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
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Save Buyer</button>
                        <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
                    </div>
                    <div class="loading mt-3">Saving buyer data...</div>
                    <div class="error-message"></div>
                    <div class="success-message"></div>
                </form>
            </div>
        </div>

        <!-- Buyer Details -->
        <div class="card" id="buyerDetails">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Buyer Details</h5>
                <div>
                    <button id="editBuyerBtn" class="btn btn-sm btn-primary">Edit</button>
                    <button id="backToListBtn" class="btn btn-sm btn-secondary">Back to List</button>
                </div>
            </div>
            <div class="card-body">
                <div class="loading">Loading buyer details...</div>
                <div class="error-message"></div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <h4 id="detailName">Buyer Details</h4>
                        <p><strong>Customer Number:</strong> <span id="detailCustomerNumber"></span></p>
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
                        <h4>Purchase History</h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>VIN</th>
                                        <th>Vehicle</th>
                                        <th>Purchase Date</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="purchaseHistoryTableBody">
                                    <!-- Purchase history will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buyers List -->
        <div class="card">
            <div class="card-header">
                <h5>Buyers List</h5>
            </div>
            <div class="card-body">
                <div class="loading">Loading buyers...</div>
                <div class="error-message"></div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer Number</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="buyersTableBody">
                            <!-- Buyers will be loaded here -->
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
            const buyersTableBody = document.getElementById('buyersTableBody');
            const buyerForm = document.getElementById('buyerForm');
            const buyerDetails = document.getElementById('buyerDetails');
            const buyerDataForm = document.getElementById('buyerDataForm');
            const showFormBtn = document.getElementById('showFormBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const formTitle = document.getElementById('formTitle');
            const backToListBtn = document.getElementById('backToListBtn');
            const editBuyerBtn = document.getElementById('editBuyerBtn');
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');

            // Form fields
            const buyerIdField = document.getElementById('buyerId');
            const customerNumberField = document.getElementById('customer_number');
            const addressIdField = document.getElementById('address_id');
            const firstNameField = document.getElementById('first_name');
            const lastNameField = document.getElementById('last_name');
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

            // Fetch all buyers
            function fetchBuyers(searchTerm = '') {
                showLoading();
                hideError();

                let url = '/api/buyers';
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
                            throw new Error('Failed to fetch buyers');
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        renderBuyers(data);
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error loading buyers: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Render buyers in the table
            function renderBuyers(buyers) {
                buyersTableBody.innerHTML = '';

                if (buyers.length === 0) {
                    const row = document.createElement('tr');
                    row.innerHTML = '<td colspan="6" class="text-center">No buyers found</td>';
                    buyersTableBody.appendChild(row);
                    return;
                }

                buyers.forEach(buyer => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${buyer.customer_id}</td>
                        <td>${buyer.customer_number}</td>
                        <td>${buyer.first_name} ${buyer.last_name}</td>
                        <td>${buyer.email}</td>
                        <td>${buyer.phone_number}</td>
                        <td>
                            <button class="btn btn-sm btn-info view-btn" data-id="${buyer.customer_id}">View</button>
                            <button class="btn btn-sm btn-primary edit-btn" data-id="${buyer.customer_id}">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${buyer.customer_id}">Delete</button>
                        </td>
                    `;
                    buyersTableBody.appendChild(row);
                });

                // Add event listeners to buttons
                document.querySelectorAll('.view-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        viewBuyer(id);
                    });
                });

                document.querySelectorAll('.edit-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        editBuyer(id);
                    });
                });

                document.querySelectorAll('.delete-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        deleteBuyer(id);
                    });
                });
            }

            // Load addresses for dropdown
            function loadAddresses() {
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
                            addressIdField.innerHTML +=
                                `<option value="${address.address_id}">${address.street} ${address.house_number}, ${address.postal_code} ${address.city}, ${address.country}</option>`;
                        });
                    })
                    .catch(error => console.error('Error loading addresses:', error));
            }

            // Show form for adding a new buyer
            showFormBtn.addEventListener('click', function() {
                formTitle.textContent = 'Add New Buyer';
                buyerIdField.value = '';
                buyerDataForm.reset();
                buyerForm.style.display = 'block';
                buyerDetails.style.display = 'none';
                showFormBtn.style.display = 'none';

                // Load addresses for dropdown
                loadAddresses();

                // Generate a random customer number
                const randomNum = Math.floor(10000 + Math.random() * 90000);
                customerNumberField.value = 'B' + randomNum;
            });

            // Hide form
            cancelBtn.addEventListener('click', function() {
                buyerForm.style.display = 'none';
                showFormBtn.style.display = 'block';
                hideError();
            });

            // Back to list from details view
            backToListBtn.addEventListener('click', function() {
                buyerDetails.style.display = 'none';
                showFormBtn.style.display = 'block';
            });

            // Edit from details view
            editBuyerBtn.addEventListener('click', function() {
                const id = buyerIdField.value;
                editBuyer(id);
            });

            // Handle form submission
            buyerDataForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const buyerId = buyerIdField.value;
                const buyerData = {
                    customer_number: customerNumberField.value,
                    address_id: addressIdField.value,
                    first_name: firstNameField.value,
                    last_name: lastNameField.value,
                    phone_number: phoneNumberField.value,
                    email: emailField.value
                };

                if (buyerId) {
                    updateBuyer(buyerId, buyerData);
                } else {
                    createBuyer(buyerData);
                }
            });

            // Search functionality
            searchBtn.addEventListener('click', function() {
                const searchTerm = searchInput.value.trim();
                fetchBuyers(searchTerm);
            });

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const searchTerm = searchInput.value.trim();
                    fetchBuyers(searchTerm);
                }
            });

            // Create a new buyer
            function createBuyer(buyerData) {
                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/api/buyers', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(buyerData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Failed to create buyer');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        buyerForm.style.display = 'none';
                        showFormBtn.style.display = 'block';
                        showSuccess('Buyer created successfully!');
                        fetchBuyers();
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error creating buyer: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // View buyer details
            function viewBuyer(id) {
                showLoading();
                hideError();

                fetch(`/api/buyers/${id}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch buyer details');
                        }
                        return response.json();
                    })
                    .then(buyer => {
                        hideLoading();

                        // Store buyer ID for edit button
                        buyerIdField.value = buyer.customer_id;

                        // Display buyer details
                        document.getElementById('detailName').textContent =
                            `${buyer.first_name} ${buyer.last_name}`;
                        document.getElementById('detailCustomerNumber').textContent = buyer.customer_number;
                        document.getElementById('detailPhoneNumber').textContent = buyer.phone_number;
                        document.getElementById('detailEmail').textContent = buyer.email;

                        // Format address
                        const address = buyer.address;
                        if (address) {
                            document.getElementById('detailAddress').innerHTML = `
                            ${address.street} ${address.house_number}<br>
                            ${address.postal_code} ${address.city}<br>
                            ${address.country}
                        `;
                        } else {
                            document.getElementById('detailAddress').textContent = 'No address information';
                        }

                        // Load purchase history
                        const purchaseHistoryTableBody = document.getElementById('purchaseHistoryTableBody');
                        purchaseHistoryTableBody.innerHTML = '';

                        console.log('Buyer data:', buyer);
                        console.log('Vehicles data:', buyer.vehicles);

                        if (buyer.vehicles && buyer.vehicles.length > 0) {
                            buyer.vehicles.forEach(vehicle => {
                                // Log the vehicle data to help debug
                                console.log('Vehicle details:', vehicle);

                                // Get purchase date from purchase contract if available
                                let purchaseDate = '-';
                                if (vehicle.purchase_contract && vehicle.purchase_contract
                                    .contract_date) {
                                    purchaseDate = new Date(vehicle.purchase_contract.contract_date)
                                        .toLocaleDateString();
                                } else if (vehicle.purchaseContract && vehicle.purchaseContract
                                    .contract_date) {
                                    purchaseDate = new Date(vehicle.purchaseContract.contract_date)
                                        .toLocaleDateString();
                                }

                                // Get vehicle details with fallbacks
                                let vehicleInfo = '-';
                                let manufacturerName = '-';
                                let modelName = '-';

                                if (vehicle.manufacturer && vehicle.manufacturer.name) {
                                    manufacturerName = vehicle.manufacturer.name;
                                }

                                if (vehicle.model && vehicle.model.model_name) {
                                    modelName = vehicle.model.model_name;
                                } else if (vehicle.model && vehicle.model.name) {
                                    modelName = vehicle.model.name;
                                }

                                if (vehicle.first_registration) {
                                    const year = new Date(vehicle.first_registration).getFullYear();
                                    vehicleInfo = `${year} ${manufacturerName} ${modelName}`;
                                } else {
                                    vehicleInfo = `${manufacturerName} ${modelName}`;
                                }

                                // Format price with fallbacks
                                let price = '-';
                                if (vehicle.selling_price) {
                                    price = `€${parseFloat(vehicle.selling_price).toFixed(2)}`;
                                }

                                const row = document.createElement('tr');
                                row.innerHTML = `
                                <td>${vehicle.vin}</td>
                                <td>${vehicleInfo}</td>
                                <td>${purchaseDate}</td>
                                <td>${price}</td>
                                <td>${vehicle.status || '-'}</td>
                            `;
                                purchaseHistoryTableBody.appendChild(row);
                            });
                        } else {
                            const row = document.createElement('tr');
                            row.innerHTML =
                            '<td colspan="5" class="text-center">No purchase history found</td>';
                            purchaseHistoryTableBody.appendChild(row);
                        }

                        // Show the details view
                        buyerDetails.style.display = 'block';
                        buyerForm.style.display = 'none';
                        showFormBtn.style.display = 'none';
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error loading buyer details: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Fetch buyer details for editing
            function editBuyer(id) {
                showLoading();
                hideError();

                fetch(`/api/buyers/${id}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch buyer details');
                        }
                        return response.json();
                    })
                    .then(buyer => {
                        hideLoading();

                        // Load addresses for dropdown
                        loadAddresses();

                        // Set form title and buyer ID
                        formTitle.textContent = `Edit Buyer: ${buyer.first_name} ${buyer.last_name}`;
                        buyerIdField.value = buyer.customer_id;

                        // Populate form fields
                        customerNumberField.value = buyer.customer_number;
                        firstNameField.value = buyer.first_name;
                        lastNameField.value = buyer.last_name;
                        phoneNumberField.value = buyer.phone_number;
                        emailField.value = buyer.email;

                        // Set address dropdown after a short delay to ensure it's loaded
                        setTimeout(() => {
                            if (buyer.address_id) {
                                addressIdField.value = buyer.address_id;
                            }
                        }, 500);

                        // Show the form
                        buyerForm.style.display = 'block';
                        buyerDetails.style.display = 'none';
                        showFormBtn.style.display = 'none';
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error loading buyer details: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Update an existing buyer
            function updateBuyer(id, buyerData) {
                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/api/buyers/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(buyerData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Failed to update buyer');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        buyerForm.style.display = 'none';
                        showFormBtn.style.display = 'block';
                        showSuccess('Buyer updated successfully!');
                        fetchBuyers();
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error updating buyer: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Delete a buyer
            function deleteBuyer(id) {
                if (!confirm('Are you sure you want to delete this buyer?')) {
                    return;
                }

                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/api/buyers/${id}`, {
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
                                throw new Error(data.message || 'Failed to delete buyer');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        showSuccess('Buyer deleted successfully!');
                        fetchBuyers();
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error deleting buyer: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Initial load
            fetchBuyers();
        });
    </script>
</body>

</html>
