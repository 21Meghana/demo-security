<link rel="stylesheet" href="style.css">

<?php

// Database connection
$conn = pg_connect("host=localhost dbname=phc user=postgres password=suman@242");

if (!$conn) {
    die("Database connection failed.");
}

// Decryption key
$decryption_key = 'secretKey123';

// Fetch and decrypt data from the database
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
            medical_history mh ON p.patient_id = mh.patient_id";
$result = pg_query($conn, $query);

if (pg_num_rows($result) > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr>
            <th>Patient ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Age</th>
            <th>Contact Info</th>
            <th>Medical History</th>
            <th>Action</th>
          </tr>";

    while ($row = pg_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['patient_id'] . "</td>";
        echo "<td>" . $row['first_name'] . "</td>";
        echo "<td>" . $row['last_name'] . "</td>";
        echo "<td>" . $row['age'] . "</td>";
        echo "<td>" . $row['contact_info'] . "</td>";
        echo "<td>" . $row['medical_history'] . "</td>";
        
        // Add a delete button
        echo "<td><a href='delete_data.php?id=" . $row['patient_id'] . "' onclick='return confirm(\"Are you sure you want to delete this patient?\")'>Delete</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No data found.";
}

pg_close($conn);
?>
