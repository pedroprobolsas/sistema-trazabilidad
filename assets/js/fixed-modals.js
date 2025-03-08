// Modal initialization for the fixed version
document.addEventListener('DOMContentLoaded', function() {
    // Initialize modals
    initializeModals();
    
    // Set up event listeners for reception modal
    setupReceptionModalListeners();
    
    // Set up event listeners for order modal
    setupOrderModalListeners();
    
    // Set up event listeners for other modals
    setupProductModalListeners();
    setupServiceTypeModalListeners();
    setupProviderModalListeners();
});

// Function to initialize all modals
function initializeModals() {
    // Get all modal elements
    const modalElements = document.querySelectorAll('.modal');
    
    // Initialize each modal with Bootstrap
    modalElements.forEach(modalElement => {
        new bootstrap.Modal(modalElement);
    });
}

// Function to set up reception modal listeners
function setupReceptionModalListeners() {
    // Set default reception date to today
    const receptionDateInput = document.querySelector('#receptionDate');
    if (receptionDateInput) {
        const today = new Date().toISOString().split('T')[0];
        receptionDateInput.value = today;
    }
    
    // Convert service order select to a searchable dropdown
    const serviceOrderSelect = document.querySelector('#receptionServiceOrder');
    if (serviceOrderSelect) {
        // Create a wrapper div for the search functionality
        const wrapper = document.createElement('div');
        wrapper.className = 'position-relative';
        
        // Create search input
        const searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.className = 'form-control';
        searchInput.placeholder = 'Buscar orden de servicio...';
        searchInput.id = 'receptionServiceOrderSearch';
        
        // Create dropdown for results
        const dropdown = document.createElement('div');
        dropdown.className = 'dropdown-menu w-100';
        dropdown.id = 'receptionServiceOrderDropdown';
        
        // Hide the original select
        serviceOrderSelect.style.display = 'none';
        
        // Insert the new elements
        serviceOrderSelect.parentNode.insertBefore(wrapper, serviceOrderSelect);
        wrapper.appendChild(searchInput);
        wrapper.appendChild(dropdown);
        
        // Add event listener for search input
        searchInput.addEventListener('input', function() {
            const searchText = this.value.toLowerCase();
            dropdown.innerHTML = '';
            
            // Show dropdown if there's search text
            if (searchText.length > 0) {
                dropdown.classList.add('show');
                
                // Filter options based on search text
                Array.from(serviceOrderSelect.options).forEach(option => {
                    if (option.value && option.text.toLowerCase().includes(searchText)) {
                        const item = document.createElement('a');
                        item.className = 'dropdown-item';
                        item.href = '#';
                        item.textContent = option.text;
                        item.dataset.value = option.value;
                        
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            searchInput.value = this.textContent;
                            serviceOrderSelect.value = this.dataset.value;
                            dropdown.classList.remove('show');
                            
                            // Trigger change event to update provider and product
                            const event = new Event('change');
                            serviceOrderSelect.dispatchEvent(event);
                        });
                        
                        dropdown.appendChild(item);
                    }
                });
                
                // If no results found
                if (dropdown.children.length === 0) {
                    const noResults = document.createElement('span');
                    noResults.className = 'dropdown-item disabled';
                    noResults.textContent = 'No se encontraron resultados';
                    dropdown.appendChild(noResults);
                }
            } else {
                dropdown.classList.remove('show');
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!wrapper.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
        
        // Add event listener for service order selection
        serviceOrderSelect.addEventListener('change', function() {
            if (this.value) {
                // In a real application, this would fetch data from the server
                // For now, we'll simulate this with static data
                const providerSelect = document.querySelector('#receptionProvider');
                const productSelect = document.querySelector('#receptionProduct');
                
                // Get the selected option text
                const selectedOption = this.options[this.selectedIndex].text;
                
                // Extract provider and product info from the option text
                // Format: "OS-XXXX-XXXX - Product Name (Provider Name)"
                const match = selectedOption.match(/- (.*) \((.*)\)/);
                
                if (match && match.length === 3) {
                    const productName = match[1];
                    const providerName = match[2];
                    
                    // Enable the selects first
                    if (providerSelect) providerSelect.disabled = false;
                    if (productSelect) productSelect.disabled = false;
                    
                    // Find provider option by name
                    if (providerSelect) {
                        for (let i = 0; i < providerSelect.options.length; i++) {
                            if (providerSelect.options[i].text === providerName) {
                                providerSelect.value = providerSelect.options[i].value;
                                break;
                            }
                        }
                    }
                    
                    // Find product option by name
                    if (productSelect) {
                        for (let i = 0; i < productSelect.options.length; i++) {
                            if (productSelect.options[i].text === productName) {
                                productSelect.value = productSelect.options[i].value;
                                break;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // New reception form submission
    const saveReceptionBtn = document.querySelector('#saveReceptionBtn');
    if (saveReceptionBtn) {
        saveReceptionBtn.addEventListener('click', function() {
            const form = document.querySelector('#newReceptionForm');
            if (form.checkValidity()) {
                // In a real application, this would send data to the server
                // For now, we'll just show a success message and close the modal
                
                // Close the modal
                const modal = bootstrap.Modal.getInstance(document.querySelector('#newReceptionModal'));
                modal.hide();
                
                // Show success message
                showAlert('Recepción de material registrada exitosamente', 'success');
                
                // Reset form
                form.reset();
                
                // Set default date again
                if (receptionDateInput) {
                    const today = new Date().toISOString().split('T')[0];
                    receptionDateInput.value = today;
                }
            } else {
                form.classList.add('was-validated');
            }
        });
    }
    
    // Set up view and delete buttons for receptions
    setupReceptionButtonListeners();
}

// Function to set up order modal listeners
function setupOrderModalListeners() {
    // Set default request date to today
    const requestDateInput = document.querySelector('#requestDate');
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
                // In a real application, this would send data to the server
                // For now, we'll just show a success message and close the modal
                
                // Close the modal
                const modal = bootstrap.Modal.getInstance(document.querySelector('#newOrderModal'));
                modal.hide();
                
                // Show success message
                showAlert('Orden de servicio creada exitosamente', 'success');
                
                // Reset form
                form.reset();
                
                // Set default date again
                if (requestDateInput) {
                    const today = new Date().toISOString().split('T')[0];
                    requestDateInput.value = today;
                }
            } else {
                form.classList.add('was-validated');
            }
        });
    }
    
    // Set up view and delete buttons for orders
    setupOrderButtonListeners();
}

// Function to set up product modal listeners
function setupProductModalListeners() {
    const saveProductBtn = document.querySelector('#saveProductBtn');
    if (saveProductBtn) {
        saveProductBtn.addEventListener('click', function() {
            const form = document.querySelector('#newProductForm');
            if (form.checkValidity()) {
                // Close the modal
                const modal = bootstrap.Modal.getInstance(document.querySelector('#newProductModal'));
                modal.hide();
                
                // Show success message
                showAlert('Producto creado exitosamente', 'success');
                
                // Reset form
                form.reset();
            } else {
                form.classList.add('was-validated');
            }
        });
    }
}

// Function to set up service type modal listeners
function setupServiceTypeModalListeners() {
    const saveServiceTypeBtn = document.querySelector('#saveServiceTypeBtn');
    if (saveServiceTypeBtn) {
        saveServiceTypeBtn.addEventListener('click', function() {
            const form = document.querySelector('#newServiceTypeForm');
            if (form.checkValidity()) {
                // Close the modal
                const modal = bootstrap.Modal.getInstance(document.querySelector('#newServiceTypeModal'));
                modal.hide();
                
                // Show success message
                showAlert('Tipo de servicio creado exitosamente', 'success');
                
                // Reset form
                form.reset();
            } else {
                form.classList.add('was-validated');
            }
        });
    }
}

// Function to set up provider modal listeners
function setupProviderModalListeners() {
    const saveProviderBtn = document.querySelector('#saveProviderBtn');
    if (saveProviderBtn) {
        saveProviderBtn.addEventListener('click', function() {
            const form = document.querySelector('#newProviderForm');
            if (form.checkValidity()) {
                // Close the modal
                const modal = bootstrap.Modal.getInstance(document.querySelector('#newProviderModal'));
                modal.hide();
                
                // Show success message
                showAlert('Proveedor creado exitosamente', 'success');
                
                // Reset form
                form.reset();
            } else {
                form.classList.add('was-validated');
            }
        });
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
                // In a real application, this would send a delete request to the server
                // For now, we'll just remove the row and show a success message
                row.remove();
                showAlert('Recepción de material eliminada exitosamente', 'success');
            }
        });
    });
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
    
    // Delete order buttons
    const deleteButtons = document.querySelectorAll('#service-orders .delete-order');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('¿Está seguro que desea eliminar esta orden de servicio?')) {
                const row = this.closest('tr');
                // In a real application, this would send a delete request to the server
                // For now, we'll just remove the row and show a success message
                row.remove();
                showAlert('Orden de servicio eliminada exitosamente', 'success');
            }
        });
    });
}

// Function to view reception details
function viewReceptionDetails(receptionId) {
    // In a real application, this would fetch data from the server
    // For now, we'll use static data based on the ID
    
    // Find the row with the matching reception ID
    const row = document.querySelector(`#receptions .view-reception[data-id="${receptionId}"]`).closest('tr');
    
    if (row) {
        const cells = row.querySelectorAll('td');
        
        // Update modal fields with reception data
        document.getElementById('viewReceptionNumber').textContent = cells[0].textContent;
        document.getElementById('viewReceptionStatus').textContent = cells[6].querySelector('.badge').textContent;
        document.getElementById('viewReceptionStatus').className = `badge ${cells[6].querySelector('.badge').className.split(' ').filter(c => c.startsWith('bg-')).join(' ')}`;
        document.getElementById('viewReceptionServiceOrder').textContent = cells[1].textContent;
        document.getElementById('viewReceptionProvider').textContent = cells[2].textContent;
        document.getElementById('viewReceptionProduct').textContent = cells[3].textContent;
        document.getElementById('viewReceptionQuantity').textContent = cells[4].textContent;
        document.getElementById('viewReceptionDate').textContent = cells[5].textContent;
        document.getElementById('viewReceptionQuality').textContent = 'Buena'; // Default value
        document.getElementById('viewReceptionNotes').textContent = '-';
        
        // Show the modal
        const viewModal = new bootstrap.Modal(document.getElementById('viewReceptionModal'));
        viewModal.show();
    }
}

// Function to view order details
function viewOrderDetails(orderId) {
    // In a real application, this would fetch data from the server
    // For now, we'll use static data based on the ID
    
    // Find the row with the matching order ID
    const row = document.querySelector(`#service-orders .view-order[data-id="${orderId}"]`).closest('tr');
    
    if (row) {
        const cells = row.querySelectorAll('td');
        
        // Update modal fields with order data
        document.getElementById('viewOrderNumber').textContent = cells[0].textContent;
        document.getElementById('viewOrderStatus').textContent = cells[7].querySelector('.badge').textContent;
        document.getElementById('viewOrderStatus').className = `badge ${cells[7].querySelector('.badge').className.split(' ').filter(c => c.startsWith('bg-')).join(' ')}`;
        document.getElementById('viewOrderProvider').textContent = cells[1].textContent;
        document.getElementById('viewOrderProduct').textContent = cells[2].textContent;
        document.getElementById('viewOrderServiceType').textContent = cells[3].textContent;
        document.getElementById('viewOrderQuantity').textContent = cells[6].textContent;
        document.getElementById('viewOrderRequestDate').textContent = cells[4].textContent;
        document.getElementById('viewOrderDueDate').textContent = cells[5].textContent;
        document.getElementById('viewOrderContactPerson').textContent = 'No especificado';
        document.getElementById('viewOrderPriority').textContent = 'Normal';
        document.getElementById('viewOrderNotes').textContent = '-';
        
        // Show the modal
        const viewModal = new bootstrap.Modal(document.getElementById('viewOrderModal'));
        viewModal.show();
    }
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
    const content = document.querySelector('.content');
    content.insertBefore(alertDiv, content.firstChild);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(alertDiv);
        bsAlert.close();
    }, 5000);
}

// Function to print reception details
function printReceptionDetails() {
    const printWindow = window.open('', '_blank');
    const receptionContent = document.querySelector('#viewReceptionModal .modal-body').innerHTML;
    
    printWindow.document.write(`
        <html>
            <head>
                <title>Recepción de Material</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

// Function to print order details
function printOrderDetails() {
    const printWindow = window.open('', '_blank');
    const orderContent = document.querySelector('#viewOrderModal .modal-body').innerHTML;
    
    printWindow.document.write(`
        <html>
            <head>
                <title>Orden de Servicio</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <div class="mt-4 no-print">
                    <button class="btn btn-primary" onclick="window.print()">Imprimir</button>
                    <button class="btn btn-secondary" onclick="window.close()">Cerrar</button>
                </div>
            </body>
        </html>
    `);
    
    printWindow.document.close();
}

// Add event listeners for print functionality
document.addEventListener('click', function(e) {
    // Print reception button
    if (e.target.id === 'printReceptionBtn') {
        printReceptionDetails();
    }
    
    // Print order button
    if (e.target.id === 'printOrderBtn') {
        printOrderDetails();
    }
});
