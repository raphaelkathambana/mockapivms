<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Item Visualizer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 20px;
        }

        .card {
            margin-bottom: 20px;
        }

        #itemForm {
            display: none;
        }

        .loading {
            display: none;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Item Visualizer</h1>
        <div class="row">
            <div class="col-md-12 mb-3">
                <button id="showFormBtn" class="btn btn-primary">Add New Item</button>
                <a href="/" class="btn btn-secondary">Back to Home</a>
            </div>
        </div>

        <div class="card" id="itemForm">
            <div class="card-header">
                <h5 id="formTitle">Add New Item</h5>
            </div>
            <div class="card-body">
                <form id="itemDataForm">
                    <input type="hidden" id="itemId">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" required>
                    </div>
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
                </form>
                <div class="loading">Loading...</div>
                <div class="error-message"></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Items List</h5>
            </div>
            <div class="card-body">
                <div class="loading">Loading items...</div>
                <div class="error-message"></div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody">
                            <!-- Items will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const itemsTableBody = document.getElementById('itemsTableBody');
            const itemForm = document.getElementById('itemForm');
            const itemDataForm = document.getElementById('itemDataForm');
            const showFormBtn = document.getElementById('showFormBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const formTitle = document.getElementById('formTitle');
            const itemIdField = document.getElementById('itemId');
            const nameField = document.getElementById('name');
            const descriptionField = document.getElementById('description');
            const priceField = document.getElementById('price');
            const loadingElements = document.querySelectorAll('.loading');
            const errorElements = document.querySelectorAll('.error-message');

            // Show loading indicator
            function showLoading() {
                loadingElements.forEach(el => el.style.display = 'block');
            }

            // Hide loading indicator
            function hideLoading() {
                loadingElements.forEach(el => el.style.display = 'none');
            }

            // Show error message
            function showError(message) {
                errorElements.forEach(el => {
                    el.textContent = message;
                    el.style.display = 'block';
                });
            }

            // Hide error message
            function hideError() {
                errorElements.forEach(el => {
                    el.textContent = '';
                    el.style.display = 'none';
                });
            }

            // Fetch all items
            function fetchItems() {
                showLoading();
                hideError();

                fetch('/api/items', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch items');
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        renderItems(data);
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error loading items: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Render items in the table
            function renderItems(items) {
                itemsTableBody.innerHTML = '';

                if (items.length === 0) {
                    const row = document.createElement('tr');
                    row.innerHTML = '<td colspan="5" class="text-center">No items found</td>';
                    itemsTableBody.appendChild(row);
                    return;
                }

                items.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.id}</td>
                        <td>${item.name}</td>
                        <td>${item.description || '-'}</td>
                        <td>$${parseFloat(item.price).toFixed(2)}</td>
                        <td>
                            <button class="btn btn-sm btn-info edit-btn" data-id="${item.id}">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${item.id}">Delete</button>
                        </td>
                    `;
                    itemsTableBody.appendChild(row);
                });

                // Add event listeners to edit and delete buttons
                document.querySelectorAll('.edit-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        editItem(id);
                    });
                });

                document.querySelectorAll('.delete-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        deleteItem(id);
                    });
                });
            }

            // Show form for adding a new item
            showFormBtn.addEventListener('click', function() {
                formTitle.textContent = 'Add New Item';
                itemIdField.value = '';
                itemDataForm.reset();
                itemForm.style.display = 'block';
                showFormBtn.style.display = 'none';
            });

            // Hide form
            cancelBtn.addEventListener('click', function() {
                itemForm.style.display = 'none';
                showFormBtn.style.display = 'block';
                hideError();
            });

            // Handle form submission
            itemDataForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const itemId = itemIdField.value;
                const itemData = {
                    name: nameField.value,
                    description: descriptionField.value,
                    price: parseFloat(priceField.value)
                };

                if (itemId) {
                    updateItem(itemId, itemData);
                } else {
                    createItem(itemData);
                }
            });

            // Create a new item
            function createItem(itemData) {
                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/api/items', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(itemData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to create item');
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        itemForm.style.display = 'none';
                        showFormBtn.style.display = 'block';
                        fetchItems();
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error creating item: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Fetch item details for editing
            function editItem(id) {
                showLoading();
                hideError();

                fetch(`/api/items/${id}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch item details');
                        }
                        return response.json();
                    })
                    .then(item => {
                        hideLoading();
                        formTitle.textContent = 'Edit Item';
                        itemIdField.value = item.id;
                        nameField.value = item.name;
                        descriptionField.value = item.description || '';
                        priceField.value = item.price;
                        itemForm.style.display = 'block';
                        showFormBtn.style.display = 'none';
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error loading item details: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Update an existing item
            function updateItem(id, itemData) {
                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/api/items/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(itemData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to update item');
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        itemForm.style.display = 'none';
                        showFormBtn.style.display = 'block';
                        fetchItems();
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error updating item: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Delete an item
            function deleteItem(id) {
                if (!confirm('Are you sure you want to delete this item?')) {
                    return;
                }

                showLoading();
                hideError();

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/api/items/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to delete item');
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        fetchItems();
                    })
                    .catch(error => {
                        hideLoading();
                        showError('Error deleting item: ' + error.message);
                        console.error('Error:', error);
                    });
            }

            // Initial load
            fetchItems();
        });
    </script>
</body>

</html>
