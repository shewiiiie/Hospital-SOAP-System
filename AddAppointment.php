<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "dbappointmentmanagement";

// Connect to database
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch doctors for dropdown
$doctorsQuery = "SELECT id, name FROM doctors";
$doctorsResult = $conn->query($doctorsQuery);

// Fetch specialties for dropdown
$specialtyQuery = "SELECT id, name FROM specialties";
$specialtyResult = $conn->query($specialtyQuery);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_name = $conn->real_escape_string($_POST['patient_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $contact_number = $conn->real_escape_string($_POST['contact_number']);
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $doctor_id = $_POST['doctor'];
    $specialty_id = $_POST['specialty'];

    // Insert appointment using prepared statement
    $insertQuery = "INSERT INTO appointments (patient_name, email, contact_number, appointment_date, appointment_time, doctor_id, specialty_id, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'PENDING')";
    
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssssssi", $patient_name, $email, $contact_number, $appointment_date, $appointment_time, $doctor_id, $specialty_id);

    if ($stmt->execute()) {
        echo "<script>alert('Appointment added successfully!'); window.location.href='appointments.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Appointment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F8F9FA;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #1B5A6B;
            color: white;
            position: fixed;
            padding-top: 20px;
        }
        .sidebar h3, .sidebar a {
            text-align: center;
            display: block;
            padding: 15px;
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #134455;
        }
        .container {
            margin-left: 270px;
            padding: 20px;
        }
        .form-container {
            background-color: #D4EDF4;
            padding: 20px;
            border-radius: 10px;
            width: 50%;
            margin: auto;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }
        .btn-save {
            background-color: #28A745;
            color: white;
        }
        .btn-cancel {
            background-color: #DC3545;
            color: white;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h3>Admin01</h3>
    <a href="dashboard.php">Dashboard</a>
    <a href="patients.php">Patient Management</a>
    <a href="appointments.php">Appointments</a>
    <a href="soap_notes.php">SOAP Notes</a>
    <a href="settings.php">Settings</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <h2 style="background-color: #1B5A6B; color: white; padding: 10px; border-radius: 5px; text-align: center;">ADD NEW APPOINTMENT</h2>

    <div class="form-container">
        <form action="add_appointment.php" method="POST">
            <div class="form-group">
                <label>Patient's Full Name:</label>
                <input type="text" name="patient_name" required>
            </div>
            <div class="form-group">
                <label>Email Address:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Contact Number:</label>
                <input type="text" name="contact_number" required>
            </div>
            <div class="form-group">
                <label>Appointment Date & Time:</label>
                <input type="date" name="appointment_date" required>
                <input type="time" name="appointment_time" required>
            </div>
            <div class="form-group">
                <label>Doctor's Name:</label>
                <select name="doctor" required>
                    <option value="">Select Doctor</option>
                    <?php while ($row = $doctorsResult->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Specialty:</label>
                <select name="specialty" required>
                    <option value="">Select Specialty</option>
                    <?php while ($row = $specialtyResult->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="btn-container">
                <button type="submit" class="btn btn-save">Save Appointment</button>
                <button type="reset" class="btn btn-cancel">Cancel</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?>
