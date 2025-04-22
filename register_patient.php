<?php
$host = "localhost";
$dbname = "medischedule";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$first_name = $conn->real_escape_string($_POST['first_name']);
$last_name = $conn->real_escape_string($_POST['last_name']);
$email = $conn->real_escape_string($_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
$gender = $_POST['gender'];
$phone_number = $conn->real_escape_string($_POST['phone_number']);
$address = $conn->real_escape_string($_POST['address']);

$sql = "INSERT INTO patients (first_name, last_name, email, password, dob, gender, phone_number, address)
        VALUES ('$first_name', '$last_name', '$email', '$password', '$dob', '$gender', '$phone_number', '$address')";

if ($conn->query($sql) === TRUE) {
  echo "<script>alert('Registration successful!'); window.location.href = 'login_patient.php';</script>";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
