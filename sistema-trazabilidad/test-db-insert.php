<?php

// Load environment variables from .env file
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get database configuration from environment variables
$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$database = $_ENV['DB_DATABASE'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];

echo "Testing database connection and insertion...\n";
echo "Host: $host\n";
echo "Port: $port\n";
echo "Database: $database\n";
echo "Username: $username\n";
echo "Password: " . str_repeat('*', strlen($password)) . "\n\n";

try {
    // Create connection
    $conn = new mysqli($host, $username, $password, $database, $port);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    echo "Connected to database successfully!\n\n";
    
    // First, check if there are any service orders
    $sql = "SELECT id FROM service_orders LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result === false) {
        throw new Exception("Query failed: " . $conn->error);
    }
    
    if ($result->num_rows == 0) {
        echo "No service orders found. Creating a test service order first...\n";
        
        // Create a test service order
        $sql = "INSERT INTO service_orders (provider_id, product_id, quantity, service_type, request_date, due_date, status, notes, created_at, updated_at) 
                VALUES (1, 1, 100, 'Test Service', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'pendiente', 'Test service order', NOW(), NOW())";
        
        if ($conn->query($sql) === TRUE) {
            $serviceOrderId = $conn->insert_id;
            echo "Test service order created with ID: $serviceOrderId\n\n";
        } else {
            throw new Exception("Error creating service order: " . $conn->error);
        }
    } else {
        $row = $result->fetch_assoc();
        $serviceOrderId = $row["id"];
        echo "Using existing service order with ID: $serviceOrderId\n\n";
    }
    
    // Now create a test reception
    echo "Creating test reception...\n";
    
    $sql = "INSERT INTO service_receptions (service_order_id, reception_date, received_quantity_kg, received_quantity_units, 
                                          scrap_quantity_kg, scrap_quantity_units, pickup_vehicle_plate, pickup_driver_name, 
                                          pickup_driver_id, received_by, delivered_by, status, quality, notes, created_at, updated_at) 
            VALUES ($serviceOrderId, NOW(), 100, 0, 0, 0, 'TEST123', 'Test Driver', '12345678', 1, 1, 'Recibido', 'Buena', 
                   'Test reception from direct database script', NOW(), NOW())";
    
    if ($conn->query($sql) === TRUE) {
        $receptionId = $conn->insert_id;
        echo "Test reception created successfully with ID: $receptionId\n";
        
        // Update service order status
        $sql = "UPDATE service_orders SET status = 'completed' WHERE id = $serviceOrderId";
        if ($conn->query($sql) === TRUE) {
            echo "Service order status updated to 'completed'\n";
        } else {
            echo "Warning: Failed to update service order status: " . $conn->error . "\n";
        }
    } else {
        throw new Exception("Error creating reception: " . $conn->error);
    }
    
    // Verify the reception was created
    $sql = "SELECT * FROM service_receptions WHERE id = $receptionId";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "\nVerified reception in database:\n";
        echo "ID: " . $row["id"] . "\n";
        echo "Service Order ID: " . $row["service_order_id"] . "\n";
        echo "Reception Date: " . $row["reception_date"] . "\n";
        echo "Quantity: " . $row["received_quantity_kg"] . " kg\n";
        echo "Status: " . $row["status"] . "\n";
    } else {
        echo "Warning: Could not verify reception in database after insertion.\n";
    }
    
    $conn->close();
    echo "\nDatabase connection test completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
