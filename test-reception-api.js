// Test script to validate database connection and reception data insertion
const testReceptionApi = async () => {
    console.log('Testing reception API...');
    
    // Test data for a new reception
    const receptionData = {
        service_order_id: 1, // Make sure this ID exists in your database
        reception_date: new Date().toISOString().split('T')[0],
        received_quantity_kg: 100,
        status: 'Recibido',
        notes: 'Test reception from API validation script',
        quality: 'Buena',
        // Required fields with default values
        received_quantity_units: 0,
        scrap_quantity_kg: 0,
        scrap_quantity_units: 0,
        pickup_vehicle_plate: 'TEST123',
        pickup_driver_name: 'Test Driver',
        pickup_driver_id: '12345678',
        delivered_by: 1
    };
    
    console.log('Sending data:', JSON.stringify(receptionData, null, 2));
    
    try {
        // First, test the connection by getting all receptions
        console.log('Testing GET /api/receptions...');
        const getResponse = await fetch('http://localhost:8000/api/receptions');
        
        if (!getResponse.ok) {
            throw new Error(`GET request failed with status ${getResponse.status}`);
        }
        
        const receptions = await getResponse.json();
        console.log(`Successfully retrieved ${receptions.length} receptions`);
        
        // Then, test creating a new reception
        console.log('Testing POST /api/receptions...');
        const postResponse = await fetch('http://localhost:8000/api/receptions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(receptionData)
        });
        
        const responseData = await postResponse.json();
        
        if (!postResponse.ok) {
            console.error('Error response:', responseData);
            throw new Error(`POST request failed with status ${postResponse.status}`);
        }
        
        console.log('Successfully created reception:', responseData);
        console.log('Database connection and data insertion validated successfully!');
        
    } catch (error) {
        console.error('API test failed:', error.message);
        
        if (error.message.includes('Failed to fetch')) {
            console.error('Make sure the Laravel server is running on http://localhost:8000');
            console.error('You can start it with: cd sistema-trazabilidad && php artisan serve');
        }
    }
};

// Run the test
testReceptionApi();
