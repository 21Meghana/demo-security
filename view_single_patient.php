<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Patient Data</title>
</head>
<body>

<?php
// Database connection
$conn = pg_connect("host=localhost dbname=phc user=postgres password=suman@242");

if (!$conn) {
    die("Database connection failed.");
}

// Decryption key
$decryption_key = 'secretKey123';

// Get the patient ID and name from the form submission
$patient_id = $_POST['patient_id'];
$first_name = $_POST['first_name'];

// Fetch and decrypt the specific patient's data from the database
$query = "SELECT 
            p.patient_id,
            p.first_name,
            p.last_name,
            p.age,
            p.contact_info,
            pgp_sym_decrypt(mh.medical_history::bytea, '$decryption_key') AS medical_history
          FROM 
            patients p
          JOIN 
            medical_history mh ON p.patient_id = mh.patient_id
          WHERE 
            p.patient_id = $patient_id AND p.first_name = '$first_name'";
$result = pg_query($conn, $query);

if (pg_num_rows($result) > 0) {
    while ($row = pg_fetch_assoc($result)) {
        echo "<div class='patient-card'>";
        echo "<h3>Patient Details</h3>";
        echo "<p><strong>Patient ID:</strong> " . $row['patient_id'] . "</p>";
        echo "<p><strong>Name:</strong> " . $row['first_name'] . " " . $row['last_name'] . "</p>";
        echo "<p><strong>Age:</strong> " . $row['age'] . "</p>";
        echo "<p><strong>Contact Info:</strong> " . $row['contact_info'] . "</p>";
        echo "<p><strong>Medical History:</strong> " . $row['medical_history'] . "</p>";
        echo "</div>";
    }
} else {
    echo "<p class='no-data'>No data found for the specified patient.</p>";
}

pg_close($conn);
?>

</body>
</html>
