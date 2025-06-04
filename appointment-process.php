<?php
session_start();

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $doctor_id = intval($_POST['doctor_id']);
    $appointment_date = $_POST['appointment_date'];
    $patient_name = mysqli_real_escape_string($conn, $_POST['patient_name']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $nic_number = mysqli_real_escape_string($conn, $_POST['nic_number']);

    // Server-side email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with(strtolower($email), '.com')) {
        echo "<script>alert('Invalid email address. It must contain @ and end with .com.'); window.location.href='appointment.php';</script>";
        exit;
    }

    $query = "INSERT INTO appointments (user_id, doctor_id, appointment_date, phone_number, email, nic_number) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iissss", $user_id, $doctor_id, $appointment_date, $phone_number, $email, $nic_number);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Appointment booked successfully!'); window.location.href='appointment.php';</script>";
    } else {
        echo "<script>alert('Error booking appointment. Please try again.'); window.location.href='appointment.php';</script>";
    }

    mysqli_stmt_close($stmt);
} else {
    header("Location: appointment.php");
}

mysqli_close($conn);
?>