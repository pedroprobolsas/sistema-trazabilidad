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

echo "Testing database connection...\n";
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
    
    // Test query to get receptions
    $sql = "SELECT * FROM service_receptions LIMIT 5";
    $result = $conn->query($sql);
    
    if ($result === false) {
        throw new Exception("Query failed: " . $conn->error);
    }
    
    if ($result->num_rows > 0) {
        echo "Found " . $result->num_rows . " receptions:\n";
        
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "ID: " . $row["id"] . " - Service Order ID: " . $row["service_order_id"] . " - Date: " . $row["reception_date"] . "\n";
        }
    } else {
        echo "No receptions found in the database.\n";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
