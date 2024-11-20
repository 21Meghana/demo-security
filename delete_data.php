<?php
// Check if patient ID is passed
if (isset($_GET['id'])) {
    $patient_id = $_GET['id'];

    // Database connection
    $conn = pg_connect("host=localhost dbname=phc user=postgres password=suman@242");

    if (!$conn) {
        die("Database connection failed.");
    }

    // Delete the corresponding medical history first (if needed)
    $delete_history_query = "DELETE FROM medical_history WHERE patient_id = $patient_id";
    $delete_history_result = pg_query($conn, $delete_history_query);

    if ($delete_history_result) {
        // Delete the patient data
        $delete_patient_query = "DELETE FROM patients WHERE patient_id = $patient_id";
        $delete_patient_result = pg_query($conn, $delete_patient_query);

        if ($delete_patient_result) {
            echo "Patient data has been deleted successfully.";
        } else {
            echo "Error deleting patient data.";
        }
    } else {
        echo "Error deleting medical history data.";
    }

    pg_close($conn);
} else {
    echo "No patient ID provided.";
}
?>
