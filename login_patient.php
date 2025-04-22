<?php
session_start();

$host = "localhost";
$dbname = "medischedule";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM patients WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['patient'] = [
            'patient_id'    => $user['patient_id'],
            'first_name'    => $user['first_name'],
            'last_name'     => $user['last_name'],
            'email'         => $user['email'],
            'dob'           => $user['dob'],
            'gender'        => $user['gender'],
            'phone_number'  => $user['phone_number'],
            'address'       => $user['address']
        ];

        header("Location: home-patient.php");
        exit();
    } else {
        echo "<script>alert('Invalid password'); window.location.href='login-patient.html';</script>";
    }
} else {
    echo "<script>alert('Email not found'); window.location.href='login-patient.html';</script>";
}

$conn->close();
?>
