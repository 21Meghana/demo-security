<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Search Patient</title>
</head>
<body>
    <h2>Search for Patient Data</h2>
    <form action="view_single_patient.php" method="post">
        <label for="patient_id">Patient ID:</label>
        <input type="number" id="patient_id" name="patient_id" required><br><br>
        
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br><br>
        
        <input type="submit" value="Search">
    </form>
</body>
</html>
