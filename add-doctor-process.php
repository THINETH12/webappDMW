<?php
session_start();

require_once './db_connect.php';

// Verify database connection
if (!$conn) {
    error_log("add-doctor-process.php - Database connection failed: " . mysqli_connect_error());
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_full_name = mysqli_real_escape_string($conn, trim($_POST['doctor_full_name'] ?? ''));
    $doctor_contact = mysqli_real_escape_string($conn, trim($_POST['doctor_contact'] ?? ''));
    $doctor_email = mysqli_real_escape_string($conn, trim($_POST['doctor_email'] ?? ''));
    $doctor_hospital = mysqli_real_escape_string($conn, trim($_POST['doctor_hospital'] ?? ''));
    $doctor_job_title = mysqli_real_escape_string($conn, trim($_POST['doctor_job_title'] ?? ''));
    $doctor_category = mysqli_real_escape_string($conn, trim($_POST['doctor_category'] ?? ''));
    $doctor_availability = json_encode($_POST['doctor_availability'] ?? []);

    if (empty($doctor_full_name) || empty($doctor_contact) || empty($doctor_email) || empty($doctor_hospital) || empty($doctor_job_title) || empty($doctor_category)) {
        die("All fields are required.");
    }

    $sql = "INSERT INTO doctors (doctor_full_name, doctor_contact, doctor_email, doctor_hospital, doctor_job_title, doctor_category, doctor_availability, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssss", $doctor_full_name, $doctor_contact, $doctor_email, $doctor_hospital, $doctor_job_title, $doctor_category, $doctor_availability);
        if (mysqli_stmt_execute($stmt)) {
            echo "<h2>Doctor Added Successfully</h2>";
            echo "<p>Name: " . htmlspecialchars($doctor_full_name) . "</p>";
            echo "<a href='index.php'>Back to Home</a>";
        } else {
            die("Error: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);
    } else {
        die("Error preparing statement: " . mysqli_error($conn));
    }
} else {
    header("Location: index.php");
    exit();
}

mysqli_close($conn);
?>