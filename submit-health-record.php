<?php
session_start();

if (!isset($_SESSION['doctor'])) {
    header("Location: login-doctor.html");
    exit;
}

$doctor = $_SESSION['doctor'];
$doctor_id = $doctor['doctor_id'];

$host = "localhost";
$dbname = "medischedule";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['appointment_id'])) {
    $appointment_id = $_GET['appointment_id'];

    $check_sql = "SELECT * FROM health_records WHERE appointment_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $appointment_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        header("Location: health-record-exists.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $diagnosis = $_POST['diagnosis'];
        $prescription = $_POST['prescription'];
        $notes = $_POST['notes'];
        $recorded_at = date('Y-m-d H:i:s');

        $insert_sql = "INSERT INTO health_records (appointment_id, diagnosis, prescription, notes, recorded_at)
                       VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("issss", $appointment_id, $diagnosis, $prescription, $notes, $recorded_at);
        $insert_stmt->execute();

        if ($insert_stmt->affected_rows > 0) {
            $update_sql = "UPDATE appointments SET status = 'completed' WHERE appointment_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $appointment_id);
            $update_stmt->execute();

            echo "<script>alert('added health record.');</script>";
            header("Location: doctor-home.php");
            exit;
        } else {
            echo "<script>alert('Failed to add health record. Please try again later.');</script>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Health Record - MediSchedule</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand text-danger fw-bold" href="#">
        <img src="logo.png" alt="MediSchedule Logo" height="40" class="me-2" />
        MediSchedule
      </a>
      <div class="ms-auto d-flex align-items-center">
        <span class="btn btn-outline-danger"><?= htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']) ?></span>
        <a href="logout.php" class="btn btn-outline-danger ms-3">Logout</a>
      </div>
    </div>
  </nav>

  <div class="container-fluid my-5 px-4">
    <div class="p-4 bg-white rounded shadow" style="opacity: 0.96">
      <h3 class="text-center text-danger mb-4">Add Health Record</h3>

      <form action="submit-health-record.php?appointment_id=<?= $_GET['appointment_id'] ?>" method="POST">
        <div class="mb-3">
          <label for="diagnosis" class="form-label">Diagnosis</label>
          <input type="text" class="form-control" id="diagnosis" name="diagnosis" required>
        </div>
        <div class="mb-3">
          <label for="prescription" class="form-label">Prescription</label>
          <textarea class="form-control" id="prescription" name="prescription" rows="4" required></textarea>
        </div>
        <div class="mb-3">
          <label for="notes" class="form-label">Notes</label>
          <textarea class="form-control" id="notes" name="notes" rows="4"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save Health Record</button>
      </form>
    </div>
  </div>
</body>
</html>
