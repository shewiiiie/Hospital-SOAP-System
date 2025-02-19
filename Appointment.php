<?php

$host = "localhost";
$user = "root"; 
$password = "";
$database = "dbappointmentmanagement";

$conn = new mysqli($host, $user, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT a.id, a.patient_name, a.contact_number, a.appointment_date, a.appointment_time, a.status, 
               COALESCE(d.name, 'N/A') AS doctor_name, COALESCE(s.name, 'N/A') AS specialty
        FROM appointments a
        LEFT JOIN doctors d ON a.doctor_id = d.id
        LEFT JOIN specialties s ON a.specialty_id = s.id
        ORDER BY a.appointment_date, a.appointment_time";

$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Management</title>
    <style> 

    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f8f9fa;
    }

    .sidebar {
        width: 250px;
        height: 100vh;
        background-color: #1f677a;
        color: white;
        padding: 20px;
        position: fixed;
    }

    .sidebar h4 {
        text-align: center;
    }

    .sidebar .nav {
        list-style-type: none;
        padding: 0;
    }

    .sidebar .nav-item {
        margin: 10px 0;
    }

    .sidebar .nav-item a {
        color: white;
        text-decoration: none;
        display: block;
        padding: 10px;
        border-radius: 5px;
    }

    .sidebar .nav-item a:hover {
        background-color: #145060;
    }


    .main-content {
        margin-left: 270px;
        flex: 1;
        padding-left: 40px;
        
    }


    table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #1f677a;
        color: white;
    }

    .btn {
        display: inline-block;
        padding: 8px 12px;
        text-decoration: none;
        border: none;
        color: white;
        cursor: pointer;
        border-radius: 5px;
    }

    .btn-primary { background-color: #1f677a; }
    .btn-success { background-color: #28a745; }
    .btn-warning { background-color: #ffc107; color: black; }
    .btn-danger { background-color: #dc3545; }


    .text-success { color: green; }
    .text-danger { color: red; }

    .search-container {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .search-input {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 20px;
        width: 250px;
    }

    .search-btn {
        background-color: #1f677a;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 20px;
        cursor: pointer;
    }

    .search-btn:hover {
        background-color: #145060;
    }

    .add-btn {
        background-color: #1f677a;
        color: white;
        padding: 10px 15px;
        border-radius: 20px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;

    }

    .add-btn:hover {
        background-color: #145060;
    }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>Admin01</h4>
        <ul class="nav">
            <li class="nav-item"><a href="#">Patient Management</a></li>
            <li class="nav-item"><a href="#">Appointments</a></li>
            <li class="nav-item"><a href="#">SOAP Notes</a></li>
            <li class="nav-item"><a href="#">Settings</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Appointments</h2>
        <div class="search-container">
            <input type="text" id="search" class="search-input" placeholder="Search by Email or Name">
            <button class="search-btn">🔍 Search</button>
            <button class="add-btn">➕ Add Appointment</button>
        </div>

        <h4>Upcoming Appointments</h4>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient's Name</th>
                    <th>Contact Number</th>
                    <th>Doctor's Name</th>
                    <th>Specialty</th>
                    <th>Appointment Date & Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['specialty']); ?></td>
                    <td><?php echo htmlspecialchars($row['appointment_date'] . ' ' . $row['appointment_time']); ?></td>
                    <td class="<?php echo ($row['status'] == 'CONFIRMED') ? 'text-success' : 'text-danger'; ?>">
                        <?php echo htmlspecialchars($row['status']); ?>
                    </td>
                    <td>
                        <a href="edit_appointment.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-warning">Edit</a>
                        <a href="delete_appointment.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
