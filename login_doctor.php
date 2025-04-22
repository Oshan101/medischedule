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
$password_input = $_POST['password'];  

$sql = "SELECT * FROM doctors WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $doctor = $result->fetch_assoc();

    if ($password_input === $doctor['password']) {
        $_SESSION['doctor'] = [
            'doctor_id'        => $doctor['doctor_id'],
            'first_name'       => $doctor['first_name'],
            'last_name'        => $doctor['last_name'],
            'email'            => $doctor['email'],
            'specialization'   => $doctor['specialization'],
            'phone_number'     => $doctor['phone_number'],
            'address'          => $doctor['address'],
            'experience_years' => $doctor['experience_years'],
        ];

        header("Location: doctor-home.php");
        exit();
    } else {
        echo "<script>alert('Invalid password'); window.location.href='login-doctor.html';</script>";
    }
} else {
    echo "<script>alert('Doctor not found with this email'); window.location.href='login-doctor.html';</script>";
}

$conn->close();
?>
