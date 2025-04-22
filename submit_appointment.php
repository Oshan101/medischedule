<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['patient'])) {
    echo "<script>alert('Please login to book an appointment.'); window.location.href='login-patient.html';</script>";
    exit();
}

$patient_id = $_SESSION['patient']['patient_id'];
$doctor_id = $_POST['doctor_id'] ?? '';
$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';
$reason = $_POST['reason'] ?? '';
$status = 'Pending'; 

if (empty($doctor_id) || empty($date) || empty($time) || empty($reason)) {
    echo "<script>alert('Please fill all required fields.'); window.history.back();</script>";
    exit();
}

$stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason, status) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iissss", $patient_id, $doctor_id, $date, $time, $reason, $status);

if ($stmt->execute()) {
    echo "<script>alert('Appointment request submitted successfully!'); window.location.href='home-patient.php';</script>";
} else {
    echo "<script>alert('Error submitting appointment. Please try again.'); window.history.back();</script>";
}

$conn->close();
?>
