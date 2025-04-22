<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$dbname = "medischedule";
$dbuser = "root";
$dbpass = "";

$conn = new mysqli($host, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM admins WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();

    if ($password === $admin['password']) {
        $_SESSION['admin'] = $admin;

        // Redirect to dashboard
        header("Location: home-admin.php");
        exit();
    } else {
        echo "<script>alert('Incorrect password'); window.location.href='login-admin.html';</script>";
    }
} else {
    echo "<script>alert('Admin not found'); window.location.href='login-admin.html';</script>";
}

$conn->close();
?>
