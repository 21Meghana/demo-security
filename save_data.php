<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$conn = pg_connect("host=localhost dbname=phc user=postgres password=suman@242");

if (!$conn) {
    die("Database connection failed: " . pg_last_error());
} else {
    echo "Database connection successful.<br>";
}

// Retrieve form data
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$age = $_POST['age'] ?? 0;
$contact_info = $_POST['contact_info'] ?? '';
$medical_history = $_POST['medical_history'] ?? '';

// Encryption key
$encryption_key = 'secretKey123';

// Insert patient data into the patients table
$insert_patient_query = "INSERT INTO patients (first_name, last_name, age, contact_info) 
                         VALUES ('$first_name', '$last_name', $age, '$contact_info') 
                         RETURNING patient_id";
$patient_result = pg_query($conn, $insert_patient_query);

if ($patient_result) {
    $patient_id = pg_fetch_result($patient_result, 0, 'patient_id');
    echo "Patient data saved with ID: $patient_id<br>";
    
    // Insert encrypted medical history into medical_history table
    $insert_history_query = "INSERT INTO medical_history (patient_id, medical_history) 
                             VALUES ($patient_id, pgp_sym_encrypt('$medical_history', '$encryption_key'))";
    $history_result = pg_query($conn, $insert_history_query);
    
    if ($history_result) {
        echo "Medical history saved successfully.";
        // Redirect to view_data.php
        header("Location: search_patient.php");
        exit();
    } else {
        echo "Error saving medical history: " . pg_last_error($conn);
    }
} else {
    echo "Error saving patient data: " . pg_last_error($conn);
}

pg_close($conn);
?>
