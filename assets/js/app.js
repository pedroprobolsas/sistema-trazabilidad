document.addEventListener('DOMContentLoaded', function() {
    // Load components
    loadComponent('sidebar-container', 'components/sidebar.html');
    loadComponent('topbar-container', 'components/topbar.html');
    loadComponent('main-content', 'components/dashboard.html', initializeCharts);
    
    // Set up navigation
    setupNavigation();
    
    // Initialize theme
    initializeTheme();
});

// Function to load HTML components
function loadComponent(containerId, componentPath, callback) {
    fetch(componentPath)
        .then(response => response.text())
        .then(html => {
            document.getElementById(containerId).innerHTML = html;
            if (callback) callback();
            
            // Add component-specific initializations
            setupComponentListeners(componentPath);
        })
        .catch(error => console.error('Error loading component:', error));
}

// Function to set up component-specific event listeners
function setupComponentListeners(componentPath) {
    if (componentPath === 'components/orders.html') {
        // Set up orders-specific listeners
        const reportBtn = document.querySelector('#generateOrdersReport');
        if (reportBtn) {
            reportBtn.addEventListener('click', function(e) {
                e.preventDefault();
                generateOrdersReport();
            });
        }
        
        // Set up modal event listeners for orders
        setupOrderModalListeners();
    } else if (componentPath === 'components/receptions.html') {
        // Set up receptions-specific listeners
        const reportBtn = document.querySelector('#generateReceptionsReport');
        if (reportBtn) {
            reportBtn.addEventListener('click', function(e) {
                e.preventDefault();
                generateReceptionsReport();
            });
        }
        
        // Set up modal event listeners for receptions
        setupReceptionModalListeners();
    }
}
function setupOrderModalListeners() {
    // Load providers and products for dropdowns
    loadProvidersForDropdown();
    loadProductsForDropdown();
    
    // Set default request date to today
    const requestDateInput = document.querySelector('#orderRequestDate');
    if (requestDateInput) {
        const today = new Date().toISOString().split('T')[0];
        requestDateInput.value = today;
    }
    
    // New order form submission
    const saveOrderBtn = document.querySelector('#saveOrderBtn');
    if (saveOrderBtn) {
        saveOrderBtn.addEventListener('click', function() {
            const form = document.querySelector('#newOrderForm');
            if (form.checkValidity()) {
                // Collect form data
                const formData = {
                    provider_id: document.querySelector('#orderProvider').value,
                    product_id: document.querySelector('#orderProduct').value,
                    quantity: document.querySelector('#orderQuantity').value,
                    service_type: document.querySelector('#orderService').value,
                    request_date: document.querySelector('#orderRequestDate').value,
                    due_date: document.querySelector('#orderDueDate').value,
                    notes: document.querySelector('#orderNotes').value
                };
                
                // Send data to Laravel backend
    fetch('http://localhost:8000/api/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    // Close the modal
                    const modal = bootstrap.Modal.getInstance(document.querySelector('#newOrderModal'));
                    modal.hide();
                    
                    // Show success message
                    showAlert('Orden de servicio creada exitosamente', 'success');
                    
                    // Reload the component to show new data
                    setTimeout(() => {
                        loadComponent('main-content', 'components/orders.html');
                    }, 1000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Error al crear la orden de servicio', 'danger');
                });
            } else {
                form.classList.add('was-validated');
            }
        });
    }
    
    // Edit order form submission
    const updateOrderBtn = document.querySelector('#updateOrderBtn');
    if (updateOrderBtn) {
        updateOrderBtn.addEventListener('click', function() {
            const form = document.querySelector('#editOrderForm');
            if (form.checkValidity()) {
                const orderId = document.querySelector('#editOrderId').value;
                
                // Collect form data
                const formData = {
                    provider_id: document.querySelector('#editOrderProvider').value,
                    product_id: document.querySelector('#editOrderProduct').value,
                    quantity: document.querySelector('#editOrderQuantity').value,
                    service_type: document.querySelector('#editOrderService').value,
                    request_date: document.querySelector('#editOrderRequestDate').value,
                    due_date: document.querySelector('#editOrderDueDate').value,
                    status: document.querySelector('#editOrderStatus').value,
                    notes: document.querySelector('#editOrderNotes').value
                };
                
                // Send data to Laravel backend
                fetch(`http://localhost/api/orders/${orderId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    // Close the modal
                    const modal = bootstrap.Modal.getInstance(document.querySelector('#editOrderModal'));
                    modal.hide();
                    
                    // Show success message
                    showAlert('Orden de servicio actualizada exitosamente', 'success');
                    
                    // Reload the component to show updated data
                    setTimeout(() => {
                        loadComponent('main-content', 'components/orders.html');
                    }, 1000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Error al actualizar la orden de servicio', 'danger');
                });
            } else {
                form.classList.add('was-validated');
            }
        });
    }
    // Delete order buttons
    const deleteButtons = document.querySelectorAll('#service-orders .btn-danger');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('¿Está seguro que desea eliminar esta orden de servicio?')) {
                const row = this.closest('tr');
                const orderId = row.querySelector('td:first-child').textContent.split('-').pop();
                
                // Send delete request to Laravel backend
                fetch(`http://localhost/api/orders/${orderId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Show success message
                    showAlert('Orden de servicio eliminada exitosamente', 'success');
                    
                    // Remove the row from the table
                    row.remove();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Error al eliminar la orden de servicio', 'danger');
                });
            }
        });
    });
    // Load orders data from API when component is loaded
    loadOrdersData();
}
// Function to load orders data from API
function loadOrdersData() {
    fetch('http://localhost:8000/api/orders')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#service-orders table tbody');
            if (!tableBody) return;
            
            // Clear existing rows
            tableBody.innerHTML = '';
            
            // Add rows for each order
            data.forEach(order => {
                const statusClass = getStatusClass(order.status);
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>OS-${order.id.toString().padStart(4, '0')}</td>
                    <td>${order.provider_name}</td>
                    <td>${order.product_name}</td>
                    <td>${formatDate(order.request_date)}</td>
                    <td>${formatDate(order.due_date)}</td>
                    <td><span class="badge ${statusClass}">${order.status}</span></td>
                    <td>
                        <button class="btn btn-info btn-sm view-order" data-id="${order.id}">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-primary btn-sm edit-order" data-id="${order.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm delete-order" data-id="${order.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
            // Re-attach event listeners to new buttons
            setupOrderButtonListeners();
        })
        .catch(error => {
            console.error('Error loading orders:', error);
            showAlert('Error al cargar las órdenes de servicio', 'danger');
        });
}
// Helper function to get status badge class
function getStatusClass(status) {
    switch(status.toLowerCase()) {
        case 'pendiente':
            return 'bg-warning text-dark';
        case 'en proceso':
            return 'bg-info';
        case 'completada':
            return 'bg-success';
        case 'cancelada':
            return 'bg-danger';
        default:
            return 'bg-secondary';
    }
}
// Helper function to format date
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES');
}
// Function to show alerts
function showAlert(message, type = 'info') {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.setAttribute('role', 'alert');
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Add to page
    const mainContent = document.querySelector('#main-content');
    mainContent.insertBefore(alertDiv, mainContent.firstChild);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(alertDiv);
        bsAlert.close();
    }, 5000);
}
// Function to view order details
function viewOrderDetails(orderId) {
    fetch(`http://localhost:8000/api/orders/${orderId}`)
        .then(response => response.json())
        .then(order => {
            // Update modal fields with order data
            document.getElementById('viewOrderNumber').textContent = `OS-${order.id.toString().padStart(4, '0')}`;
            document.getElementById('viewOrderStatus').textContent = order.status;
            document.getElementById('viewOrderStatus').className = `badge ${getStatusClass(order.status)}`;
            document.getElementById('viewOrderProvider').textContent = order.provider_name;
            document.getElementById('viewOrderProduct').textContent = order.product_name;
            document.getElementById('viewOrderQuantity').textContent = `${order.quantity} kg`;
            document.getElementById('viewOrderService').textContent = order.service_type;
            document.getElementById('viewOrderRequestDate').textContent = formatDate(order.request_date);
            document.getElementById('viewOrderDueDate').textContent = formatDate(order.due_date);
            document.getElementById('viewOrderNotes').textContent = order.notes || '-';
            
            // Show the modal
            const viewModal = new bootstrap.Modal(document.getElementById('viewOrderModal'));
            viewModal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al cargar los detalles de la orden', 'danger');
        });
}
// Function to print order details
function printOrderDetails() {
    const printWindow = window.open('', '_blank');
    const orderContent = document.querySelector('#viewOrderModal .modal-body').innerHTML;
    
    printWindow.document.write(`
        <html>
            <head>
                <title>Orden de Servicio</title>
                <link href="assets/css/styles.css" rel="stylesheet">
                <style>
                    body { padding: 20px; }
                    @media print {
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <h2 class="mb-4">Orden de Servicio</h2>
                ${orderContent}
            </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.print();
}
// Add event listeners for view and print functionality
document.addEventListener('click', function(e) {
    // View order button
    if (e.target.closest('.view-order')) {
        const orderId = e.target.closest('.view-order').dataset.id;
        viewOrderDetails(orderId);
    }
    
    // Print order button
    if (e.target.id === 'printOrderBtn') {
        printOrderDetails();
    }
});
// Function to initialize theme
function initializeTheme() {
    // Check for saved theme preference
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-bs-theme', savedTheme);
    
    // Add theme toggle button to topbar
    const topbarContainer = document.getElementById('topbar-container');
    if (topbarContainer) {
        // Wait for topbar to load
        setTimeout(() => {
            const navbarNav = document.querySelector('.navbar-nav');
            if (navbarNav) {
                const themeToggleItem = document.createElement('li');
                themeToggleItem.className = 'nav-item dropdown no-arrow mx-1';
                themeToggleItem.innerHTML = `
                    <a class="nav-link dropdown-toggle" href="#" id="themeToggle" role="button">
                        <i class="fas fa-moon fa-fw" id="themeIcon"></i>
                    </a>
                `;
                navbarNav.prepend(themeToggleItem);
                
                // Update icon based on current theme
                updateThemeIcon(savedTheme);
                
                // Add click event listener
                document.getElementById('themeToggle').addEventListener('click', toggleTheme);
            }
        }, 500);
    }
}
// Function to toggle theme
function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-bs-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    // Update theme
    document.documentElement.setAttribute('data-bs-theme', newTheme);
    
    // Save preference
    localStorage.setItem('theme', newTheme);
    
    // Update icon
    updateThemeIcon(newTheme);
    
    // Show notification
    showAlert(`Tema cambiado a ${newTheme === 'dark' ? 'oscuro' : 'claro'}`, 'info');
}
// Function to update theme icon
function updateThemeIcon(theme) {
    const themeIcon = document.getElementById('themeIcon');
    if (themeIcon) {
        if (theme === 'dark') {
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
        } else {
            themeIcon.classList.remove('fa-sun');
            themeIcon.classList.add('fa-moon');
        }
    }
}
// Function to set up order button listeners
function setupOrderButtonListeners() {
    // View order buttons
    const viewButtons = document.querySelectorAll('#service-orders .view-order');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.id;
            viewOrderDetails(orderId);
        });
    });
    
    // Edit order buttons
    const editButtons = document.querySelectorAll('#service-orders .edit-order');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.id;
            editOrderDetails(orderId);
        });
    });
    
    // Delete order buttons
    const deleteButtons = document.querySelectorAll('#service-orders .btn-danger');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('¿Está seguro que desea eliminar esta orden de servicio?')) {
                const row = this.closest('tr');
                const orderId = this.dataset.id;
                
                // Send delete request to Laravel backend
                fetch(`http://localhost/api/orders/${orderId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Show success message
                    showAlert('Orden de servicio eliminada exitosamente', 'success');
                    
                    // Remove the row from the table
                    row.remove();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Error al eliminar la orden de servicio', 'danger');
                });
            }
        });
    });
}
// Function to set up reception modal listeners
function setupReceptionModalListeners() {
    // Load service orders for dropdown
    loadServiceOrdersForDropdown();
    
    // Set default reception date to today
    const receptionDateInput = document.querySelector('#receptionDate');
    if (receptionDateInput) {
        const today = new Date().toISOString().split('T')[0];
        receptionDateInput.value = today;
    }
    
    // Add event listener for service order selection
    const serviceOrderSelect = document.querySelector('#receptionServiceOrder');
    if (serviceOrderSelect) {
        serviceOrderSelect.addEventListener('change', function() {
            if (this.value) {
                // Get service order details and populate provider and product fields
                fetch(`http://localhost:8000/api/orders/${this.value}`)
                    .then(response => response.json())
                    .then(order => {
                        const providerSelect = document.querySelector('#receptionProvider');
                        const productSelect = document.querySelector('#receptionProduct');
                        
                        if (providerSelect) {
                            providerSelect.value = order.provider_id;
                        }
                        
                        if (productSelect) {
                            productSelect.value = order.product_id;
                        }
                    })
                    .catch(error => {
                        console.error('Error loading order details:', error);
                    });
            }
        });
    }
    
    // New reception form submission
    const saveReceptionBtn = document.querySelector('#saveReceptionBtn');
    if (saveReceptionBtn) {
        saveReceptionBtn.addEventListener('click', function() {
            const form = document.querySelector('#newReceptionForm');
            if (form.checkValidity()) {
                // Collect form data
                const formData = {
                    service_order_id: document.querySelector('#receptionServiceOrder').value,
                    received_quantity_kg: document.querySelector('#receptionQuantity').value,
                    reception_date: document.querySelector('#receptionDate').value,
                    quality: document.querySelector('#receptionQuality').value,
                    status: document.querySelector('#receptionStatus').value,
                    notes: document.querySelector('#receptionNotes').value,
                    // Add required fields with default values
                    received_quantity_units: 0,
                    scrap_quantity_kg: 0,
                    scrap_quantity_units: 0,
                    pickup_vehicle_plate: 'N/A',
                    pickup_driver_name: 'N/A',
                    pickup_driver_id: 'N/A',
                    delivered_by: 1
                };
                
                console.log('Sending reception data:', formData);
                
                // Send data to Laravel backend
                fetch('http://localhost:8000/api/receptions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    
                    // Close the modal
                    const modal = bootstrap.Modal.getInstance(document.querySelector('#newReceptionModal'));
                    modal.hide();
                    
                    // Show success message
                    showAlert('Recepción de material registrada exitosamente', 'success');
                    
                    // Reload the component to show new data
                    setTimeout(() => {
                        loadComponent('main-content', 'components/receptions.html');
                    }, 1000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Error al registrar la recepción de material', 'danger');
                });
            } else {
                form.classList.add('was-validated');
            }
        });
    }
    
    // Load receptions data from API when component is loaded
    loadReceptionsData();
}

// Function to load receptions data from API
function loadReceptionsData() {
    fetch('http://localhost:8000/api/receptions')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#receptions table tbody');
            if (!tableBody) return;
            
            // Clear existing rows
            tableBody.innerHTML = '';
            
            // Add rows for each reception
            data.forEach(reception => {
                const statusClass = getReceptionStatusClass(reception.status);
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>REC-${reception.id.toString().padStart(4, '0')}</td>
                    <td>${reception.service_order_number || 'N/A'}</td>
                    <td>${reception.provider_name}</td>
                    <td>${reception.product_name}</td>
                    <td>${reception.received_quantity_kg} kg</td>
                    <td>${formatDate(reception.reception_date)}</td>
                    <td><span class="badge ${statusClass}">${reception.status}</span></td>
                    <td>
                        <button class="btn btn-info btn-sm view-reception" data-id="${reception.id}">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-danger btn-sm delete-reception" data-id="${reception.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
            
            // Set up reception button listeners
            setupReceptionButtonListeners();
        })
        .catch(error => {
            console.error('Error loading receptions:', error);
            showAlert('Error al cargar las recepciones de material', 'danger');
        });
}

// Helper function to get reception status badge class
function getReceptionStatusClass(status) {
    switch(status.toLowerCase()) {
        case 'recibido':
            return 'bg-info';
        case 'en revisión':
            return 'bg-warning text-dark';
        case 'aprobado':
            return 'bg-success';
        case 'rechazado':
            return 'bg-danger';
        default:
            return 'bg-secondary';
    }
}

// Function to set up reception button listeners
function setupReceptionButtonListeners() {
    // View reception buttons
    const viewButtons = document.querySelectorAll('#receptions .view-reception');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const receptionId = this.dataset.id;
            viewReceptionDetails(receptionId);
        });
    });
    
    // Delete reception buttons
    const deleteButtons = document.querySelectorAll('#receptions .delete-reception');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('¿Está seguro que desea eliminar esta recepción de material?')) {
                const row = this.closest('tr');
                const receptionId = this.dataset.id;
                
                // Send delete request to Laravel backend
                fetch(`http://localhost/api/receptions/${receptionId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Show success message
                    showAlert('Recepción de material eliminada exitosamente', 'success');
                    
                    // Remove the row from the table
                    row.remove();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Error al eliminar la recepción de material', 'danger');
                });
            }
        });
    });
}
// Function to view reception details
function viewReceptionDetails(receptionId) {
    fetch(`http://localhost/api/receptions/${receptionId}`)
        .then(response => response.json())
        .then(reception => {
            // Update modal fields with reception data
            document.getElementById('viewReceptionNumber').textContent = `REC-${reception.id.toString().padStart(4, '0')}`;
            document.getElementById('viewReceptionStatus').textContent = reception.status;
            document.getElementById('viewReceptionStatus').className = `badge ${getReceptionStatusClass(reception.status)}`;
            document.getElementById('viewReceptionServiceOrder').textContent = reception.service_order_number || 'N/A';
            document.getElementById('viewReceptionProvider').textContent = reception.provider_name;
            document.getElementById('viewReceptionProduct').textContent = reception.product_name;
            document.getElementById('viewReceptionQuantity').textContent = `${reception.received_quantity_kg} kg`;
            document.getElementById('viewReceptionDate').textContent = formatDate(reception.reception_date);
            document.getElementById('viewReceptionQuality').textContent = reception.quality;
            document.getElementById('viewReceptionNotes').textContent = reception.notes || '-';
            
            // Show the modal
            const viewModal = new bootstrap.Modal(document.getElementById('viewReceptionModal'));
            viewModal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al cargar los detalles de la recepción', 'danger');
        });
}

// Function to generate receptions report
function generateReceptionsReport() {
    // Get all receptions data
    fetch('http://localhost/api/receptions')
        .then(response => response.json())
        .then(data => {
            // Create a new window for the report
            const reportWindow = window.open('', '_blank');
            
            // Generate HTML for the report
            let reportContent = `
                <html>
                    <head>
                        <title>Reporte de Recepciones</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                        <style>
                            body { padding: 20px; }
                            @media print {
                                .no-print { display: none; }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h2>Reporte de Recepciones de Material</h2>
                                <button class="btn btn-primary no-print" onclick="window.print()">Imprimir</button>
                            </div>
                            <div class="mb-4">
                                <p><strong>Fecha de generación:</strong> ${new Date().toLocaleDateString('es-ES')}</p>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Recepción #</th>
                                        <th>Orden #</th>
                                        <th>Proveedor</th>
                                        <th>Producto</th>
                                        <th>Cantidad (kg)</th>
                                        <th>Fecha</th>
                                        <th>Calidad</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
            `;
            
            // Add rows for each reception
            data.forEach(reception => {
                reportContent += `
                    <tr>
                        <td>REC-${reception.id.toString().padStart(4, '0')}</td>
                        <td>${reception.service_order_number || 'N/A'}</td>
                        <td>${reception.provider_name}</td>
                        <td>${reception.product_name}</td>
                        <td>${reception.received_quantity_kg} kg</td>
                        <td>${formatDate(reception.reception_date)}</td>
                        <td>${reception.quality}</td>
                        <td>${reception.status}</td>
                    </tr>
                `;
            });
            
            // Close the HTML
            reportContent += `
                                </tbody>
                            </table>
                        </div>
                    </body>
                </html>
            `;
            
            // Write to the new window
            reportWindow.document.write(reportContent);
            reportWindow.document.close();
        })
        .catch(error => {
            console.error('Error generating report:', error);
            showAlert('Error al generar el reporte de recepciones', 'danger');
        });
}

// Add event listeners for reception view and print functionality
document.addEventListener('click', function(e) {
    // Print reception button
    if (e.target.id === 'printReceptionBtn') {
        printReceptionDetails();
    }
});

// Function to load providers for dropdown with specific element ID
function loadProvidersForDropdown(elementId = 'orderProvider') {
    const providerSelect = document.querySelector(`#${elementId}`);
    if (!providerSelect) return;
    
    fetch('http://localhost/api/providers')
        .then(response => response.json())
        .then(providers => {
            // Clear existing options except the first one
            while (providerSelect.options.length > 1) {
                providerSelect.remove(1);
            }
            
            // Add new options
            providers.forEach(provider => {
                const option = document.createElement('option');
                option.value = provider.id;
                option.textContent = provider.name;
                providerSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading providers:', error);
            // Fallback to static options if API fails
        });
}

// Function to load products for dropdown with specific element ID
function loadProductsForDropdown(elementId = 'orderProduct') {
    const productSelect = document.querySelector(`#${elementId}`);
    if (!productSelect) return;
    
    fetch('http://localhost/api/products')
        .then(response => response.json())
        .then(products => {
            // Clear existing options except the first one
            while (productSelect.options.length > 1) {
                productSelect.remove(1);
            }
            
            // Add new options
            products.forEach(product => {
                const option = document.createElement('option');
                option.value = product.id;
                option.textContent = product.name;
                productSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading products:', error);
            // Fallback to static options if API fails
        });
}

// Function to load service orders for dropdown
function loadServiceOrdersForDropdown(elementId = 'receptionServiceOrder') {
    const orderSelect = document.querySelector(`#${elementId}`);
    if (!orderSelect) return;
    
    fetch('http://localhost/api/orders')
        .then(response => response.json())
        .then(orders => {
            // Clear existing options except the first one
            while (orderSelect.options.length > 1) {
                orderSelect.remove(1);
            }
            
            // Filter orders that are pending or in process
            const availableOrders = orders.filter(order => 
                order.status.toLowerCase() === 'pendiente' || 
                order.status.toLowerCase() === 'en proceso'
            );
            
            // Add new options
            availableOrders.forEach(order => {
                const option = document.createElement('option');
                option.value = order.id;
                option.textContent = `OS-${order.id.toString().padStart(4, '0')} - ${order.product_name} (${order.provider_name})`;
                orderSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading service orders:', error);
            // Fallback to static options if API fails
        });
}
// Function to print reception details
function printReceptionDetails() {
    const printWindow = window.open('', '_blank');
    const receptionContent = document.querySelector('#viewReceptionModal .modal-body').innerHTML;
    
    printWindow.document.write(`
        <html>
            <head>
                <title>Recepción de Material</title>
                <link href="assets/css/styles.css" rel="stylesheet">
                <style>
                    body { padding: 20px; }
                    @media print {
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <h2 class="mb-4">Recepción de Material</h2>
                ${receptionContent}
                <div class="mt-4 no-print">
                    <button class="btn btn-primary" onclick="window.print()">Imprimir</button>
                    <button class="btn btn-secondary" onclick="window.close()">Cerrar</button>
                </div>
            </body>
        </html>
    `);
    
    printWindow.document.close();
}
